"""CRUD Users."""
from datetime import datetime

from flask import Blueprint, g, request

from app.extensions import db
from app.models.user import User
from app.services import auth_service, mfa_service, permission_service
from app.utils.decorators import jwt_required, role_required
from app.utils.responses import error, success

bp = Blueprint("users", __name__)

FIELDS = [
    "role_id", "site_id", "username", "etat", "nom", "prenom",
    "poste", "direction", "frequence_mobilite", "code", "tel", "statut",
]


@bp.get("")
@jwt_required
def list_users():
    if not permission_service.can(g.current_user, "users", "read"):
        return error("Accès refusé", 403)
    q = User.query
    role_id = request.args.get("role_id", type=int)
    site_id = request.args.get("site_id", type=int)
    if role_id:
        q = q.filter_by(role_id=role_id)
    if site_id:
        q = q.filter_by(site_id=site_id)
    search = request.args.get("q")
    if search:
        like = f"%{search}%"
        q = q.filter(
            db.or_(
                User.username.ilike(like),
                User.nom.ilike(like),
                User.prenom.ilike(like),
            )
        )
    items = []
    for u in q.order_by(User.id.desc()).all():
        data = u.to_dict()
        if u.site_id:
            from app.models.site import Site

            site = db.session.get(Site, u.site_id)
            if site:
                data["site"] = {"id": site.id, "site": site.site, "ville_id": site.ville_id}
        items.append(data)
    return success(items)


@bp.get("/me")
@jwt_required
def me():
    user = g.current_user
    return success({**user.to_dict(), **mfa_service.mfa_flags(user)})


@bp.get("/<int:user_id>")
@jwt_required
def get_user(user_id):
    """Détail utilisateur (lecture) — accessible à tout utilisateur authentifié."""
    user = db.session.get(User, user_id)
    if not user:
        return error("Utilisateur introuvable", 404)
    data = {**user.to_dict(), **mfa_service.mfa_flags(user)}
    if user.site_id:
        from app.models.site import Site

        site = db.session.get(Site, user.site_id)
        if site:
            data["site"] = site.to_dict()
    return success(data)


@bp.post("")
@role_required("Admin")
def create_user():
    data = request.get_json(silent=True) or {}
    password = data.get("password") or ""
    errs = auth_service.validate_password_strength(password, data.get("username"))
    if errs:
        return error("; ".join(errs), 400)
    if not data.get("role_id"):
        return error("role_id requis", 400)
    user = User(
        role_id=data["role_id"],
        site_id=data.get("site_id") or 0,
        username=data.get("username"),
        password=auth_service.hash_password(password),
        etat=data.get("etat") or "Actif",
        nom=data.get("nom"),
        prenom=data.get("prenom"),
        poste=data.get("poste"),
        direction=data.get("direction"),
        frequence_mobilite=data.get("frequence_mobilite"),
        code=data.get("code"),
        tel=data.get("tel"),
        statut=data.get("statut"),
        created=datetime.utcnow(),
        totp_enabled=False,
    )
    db.session.add(user)
    db.session.commit()
    return success(user.to_dict(), status=201)


@bp.patch("/<int:user_id>")
@role_required("Admin")
def update_user(user_id):
    user = db.session.get(User, user_id)
    if not user:
        return error("Utilisateur introuvable", 404)
    data = request.get_json(silent=True) or {}
    for f in FIELDS:
        if f in data:
            setattr(user, f, data[f])
    if data.get("password"):
        errs = auth_service.validate_password_strength(data["password"], user.username)
        if errs:
            return error("; ".join(errs), 400)
        user.password = auth_service.hash_password(data["password"])
    db.session.commit()
    return success(user.to_dict())


@bp.delete("/<int:user_id>")
@role_required("Admin")
def delete_user(user_id):
    user = db.session.get(User, user_id)
    if not user:
        return error("Utilisateur introuvable", 404)
    if user.id == g.current_user.id:
        return error("Impossible de supprimer son propre compte", 400)
    db.session.delete(user)
    db.session.commit()
    return success(message="Supprimé")
