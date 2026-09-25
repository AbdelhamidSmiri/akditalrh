"""CRUD Villes."""
from flask import Blueprint, g, request

from app.extensions import db
from app.models.ville import Ville
from app.services import permission_service
from app.utils.decorators import jwt_required, role_required
from app.utils.responses import error, success

bp = Blueprint("villes", __name__)


@bp.get("")
@jwt_required
def list_villes():
    if not permission_service.can(g.current_user, "villes", "read"):
        return error("Accès refusé", 403)
    return success([v.to_dict() for v in Ville.query.order_by(Ville.ville).all()])


@bp.get("/<int:ville_id>")
@jwt_required
def get_ville(ville_id):
    ville = db.session.get(Ville, ville_id)
    if not ville:
        return error("Ville introuvable", 404)
    return success(ville.to_dict())


@bp.post("")
@role_required("Admin")
def create_ville():
    data = request.get_json(silent=True) or {}
    ville = Ville(ville=data.get("ville"))
    db.session.add(ville)
    db.session.commit()
    return success(ville.to_dict(), status=201)


@bp.patch("/<int:ville_id>")
@role_required("Admin")
def update_ville(ville_id):
    ville = db.session.get(Ville, ville_id)
    if not ville:
        return error("Ville introuvable", 404)
    data = request.get_json(silent=True) or {}
    if "ville" in data:
        ville.ville = data["ville"]
    db.session.commit()
    return success(ville.to_dict())


@bp.delete("/<int:ville_id>")
@role_required("Admin")
def delete_ville(ville_id):
    ville = db.session.get(Ville, ville_id)
    if not ville:
        return error("Ville introuvable", 404)
    db.session.delete(ville)
    db.session.commit()
    return success(message="Supprimé")
