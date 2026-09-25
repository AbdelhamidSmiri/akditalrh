"""CRUD Cartes carburant + import."""
from datetime import datetime

from flask import Blueprint, g, request

from app.extensions import db
from app.models.carburant import CarteCarburant
from app.models.site import Site
from app.models.user import User
from app.services import import_service, permission_service
from app.utils.decorators import jwt_required
from app.utils.responses import error, success

bp = Blueprint("carburant", __name__)

FIELDS = [
    "user_id", "site_id", "prestataire", "identifiant", "carte_labelle",
    "utilisateur", "nv_plafond", "type_mt", "status",
]


def _carte_dict(c):
    data = c.to_dict()
    user = db.session.get(User, c.user_id) if c.user_id else None
    site = db.session.get(Site, c.site_id) if c.site_id else None
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
def list_cartes():
    if not permission_service.can(g.current_user, "carburant", "read"):
        return error("Accès refusé", 403)
    return success([
        _carte_dict(c)
        for c in CarteCarburant.query.order_by(CarteCarburant.id.desc()).all()
    ])


@bp.get("/<int:cid>")
@jwt_required
def get_carte(cid):
    if not permission_service.can(g.current_user, "carburant", "read"):
        return error("Accès refusé", 403)
    c = db.session.get(CarteCarburant, cid)
    if not c:
        return error("Carte introuvable", 404)
    return success(_carte_dict(c))


@bp.post("")
@jwt_required
def create_carte():
    if not permission_service.can(g.current_user, "carburant", "write"):
        return error("Accès refusé", 403)
    data = request.get_json(silent=True) or {}
    now = datetime.utcnow()
    c = CarteCarburant(
        user_id=data.get("user_id") or g.current_user.id,
        site_id=data.get("site_id") or 0,
        prestataire=data.get("prestataire") or "AFRIQUIA",
        identifiant=data.get("identifiant") or "",
        carte_labelle=data.get("carte_labelle") or "",
        utilisateur=data.get("utilisateur") or "",
        nv_plafond=data.get("nv_plafond"),
        type_mt=data.get("type_mt"),
        status=data.get("status") or "Active",
        created=now,
        modified=now,
    )
    db.session.add(c)
    db.session.commit()
    return success(_carte_dict(c), status=201)


@bp.patch("/<int:cid>")
@jwt_required
def update_carte(cid):
    if not permission_service.can(g.current_user, "carburant", "write"):
        return error("Accès refusé", 403)
    c = db.session.get(CarteCarburant, cid)
    if not c:
        return error("Carte introuvable", 404)
    data = request.get_json(silent=True) or {}
    for f in FIELDS:
        if f in data:
            setattr(c, f, data[f])
    c.modified = datetime.utcnow()
    db.session.commit()
    return success(_carte_dict(c))


@bp.delete("/<int:cid>")
@jwt_required
def delete_carte(cid):
    if not permission_service.can(g.current_user, "carburant", "write"):
        return error("Accès refusé", 403)
    c = db.session.get(CarteCarburant, cid)
    if not c:
        return error("Carte introuvable", 404)
    db.session.delete(c)
    db.session.commit()
    return success(message="Supprimé")


@bp.post("/import")
@jwt_required
def import_cartes():
    if not permission_service.can(g.current_user, "carburant", "write"):
        return error("Accès refusé", 403)
    f = request.files.get("file")
    if not f:
        return error("Fichier Excel requis (file)", 400)
    rows = import_service.parse_cartes_carburant(f)
    now = datetime.utcnow()
    for row in rows:
        db.session.add(CarteCarburant(**row, created=now, modified=now))
    db.session.commit()
    return success({"imported": len(rows)}, status=201)
