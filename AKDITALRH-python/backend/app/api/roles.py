"""CRUD Roles."""
from flask import Blueprint, g, request

from app.extensions import db
from app.models.role import Role
from app.services import permission_service
from app.utils.decorators import jwt_required, role_required
from app.utils.responses import error, success

bp = Blueprint("roles", __name__)


@bp.get("")
@jwt_required
def list_roles():
    if not permission_service.can(g.current_user, "roles", "read") and not permission_service.is_admin(g.current_user):
        # Lecture limitée pour formulaires users
        if not permission_service.can(g.current_user, "users", "read"):
            return error("Accès refusé", 403)
    return success([r.to_dict() for r in Role.query.order_by(Role.id).all()])


@bp.get("/<int:role_id>")
@jwt_required
def get_role(role_id):
    role = db.session.get(Role, role_id)
    if not role:
        return error("Rôle introuvable", 404)
    return success(role.to_dict())


@bp.post("")
@role_required("Admin")
def create_role():
    data = request.get_json(silent=True) or {}
    role = Role(role=data.get("role"), plafond_hotel=str(data.get("plafond_hotel", "0")))
    db.session.add(role)
    db.session.commit()
    return success(role.to_dict(), status=201)


@bp.patch("/<int:role_id>")
@role_required("Admin")
def update_role(role_id):
    role = db.session.get(Role, role_id)
    if not role:
        return error("Rôle introuvable", 404)
    data = request.get_json(silent=True) or {}
    if "role" in data:
        role.role = data["role"]
    if "plafond_hotel" in data:
        role.plafond_hotel = str(data["plafond_hotel"])
    db.session.commit()
    return success(role.to_dict())


@bp.delete("/<int:role_id>")
@role_required("Admin")
def delete_role(role_id):
    role = db.session.get(Role, role_id)
    if not role:
        return error("Rôle introuvable", 404)
    db.session.delete(role)
    db.session.commit()
    return success(message="Supprimé")
