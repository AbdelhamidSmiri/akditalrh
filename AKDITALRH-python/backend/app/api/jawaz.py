"""CRUD TAG Jawaz + import."""
from flask import Blueprint, g, request

from app.extensions import db
from app.models.jawaz import TagJawaz
from app.models.site import Site
from app.models.user import User
from app.services import import_service, permission_service
from app.utils.decorators import jwt_required
from app.utils.responses import error, success

bp = Blueprint("jawaz", __name__)


def _tag_dict(t):
    data = t.to_dict()
    user = db.session.get(User, t.user_id) if t.user_id else None
    site = db.session.get(Site, t.site_id) if t.site_id else None
    data["user"] = {
        "id": user.id,
        "nom": user.nom,
        "prenom": user.prenom,
        "username": user.username,
    } if user else None
    data["site"] = {"id": site.id, "site": site.site} if site else None
    return data


@bp.get("")
@jwt_required
def list_tags():
    if not permission_service.can(g.current_user, "jawaz", "read"):
        return error("Accès refusé", 403)
    return success([
        _tag_dict(t) for t in TagJawaz.query.order_by(TagJawaz.id.desc()).all()
    ])


@bp.get("/<int:tid>")
@jwt_required
def get_tag(tid):
    if not permission_service.can(g.current_user, "jawaz", "read"):
        return error("Accès refusé", 403)
    t = db.session.get(TagJawaz, tid)
    if not t:
        return error("TAG introuvable", 404)
    return success(_tag_dict(t))


@bp.post("")
@jwt_required
def create_tag():
    if not permission_service.can(g.current_user, "jawaz", "write"):
        return error("Accès refusé", 403)
    data = request.get_json(silent=True) or {}
    t = TagJawaz(
        user_id=data.get("user_id") or g.current_user.id,
        site_id=data.get("site_id") or g.current_user.site_id or 0,
        ref_jawaz=data.get("ref_jawaz") or "",
    )
    db.session.add(t)
    db.session.commit()
    return success(_tag_dict(t), status=201)


@bp.patch("/<int:tid>")
@jwt_required
def update_tag(tid):
    if not permission_service.can(g.current_user, "jawaz", "write"):
        return error("Accès refusé", 403)
    t = db.session.get(TagJawaz, tid)
    if not t:
        return error("TAG introuvable", 404)
    data = request.get_json(silent=True) or {}
    for f in ("user_id", "site_id", "ref_jawaz"):
        if f in data:
            setattr(t, f, data[f])
    db.session.commit()
    return success(_tag_dict(t))


@bp.delete("/<int:tid>")
@jwt_required
def delete_tag(tid):
    if not permission_service.can(g.current_user, "jawaz", "write"):
        return error("Accès refusé", 403)
    t = db.session.get(TagJawaz, tid)
    if not t:
        return error("TAG introuvable", 404)
    db.session.delete(t)
    db.session.commit()
    return success(message="Supprimé")


@bp.post("/import")
@jwt_required
def import_tags():
    if not permission_service.can(g.current_user, "jawaz", "write"):
        return error("Accès refusé", 403)
    f = request.files.get("file")
    if not f:
        return error("Fichier Excel requis (file)", 400)
    rows = import_service.parse_tag_jawaz(f)
    for row in rows:
        db.session.add(TagJawaz(**row))
    db.session.commit()
    return success({"imported": len(rows)}, status=201)
