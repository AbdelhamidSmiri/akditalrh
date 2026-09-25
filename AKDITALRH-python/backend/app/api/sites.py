"""CRUD Sites."""
from datetime import datetime

from flask import Blueprint, g, request

from app.extensions import db
from app.models.site import Site
from app.services import permission_service
from app.utils.decorators import jwt_required, role_required
from app.utils.responses import error, success

bp = Blueprint("sites", __name__)
FIELDS = ["ville_id", "site", "adresse", "telephone", "mail"]


@bp.get("")
@jwt_required
def list_sites():
    if not permission_service.can(g.current_user, "sites", "read"):
        return error("Accès refusé", 403)
    q = Site.query
    ville_id = request.args.get("ville_id", type=int)
    if ville_id:
        q = q.filter_by(ville_id=ville_id)
    return success([s.to_dict() for s in q.order_by(Site.id).all()])


@bp.get("/<int:site_id>")
@jwt_required
def get_site(site_id):
    site = db.session.get(Site, site_id)
    if not site:
        return error("Site introuvable", 404)
    return success(site.to_dict())


@bp.post("")
@role_required("Admin")
def create_site():
    data = request.get_json(silent=True) or {}
    site = Site(
        ville_id=data.get("ville_id"),
        site=data.get("site"),
        adresse=data.get("adresse"),
        telephone=data.get("telephone"),
        mail=data.get("mail"),
        created=datetime.utcnow(),
    )
    db.session.add(site)
    db.session.commit()
    return success(site.to_dict(), status=201)


@bp.patch("/<int:site_id>")
@role_required("Admin")
def update_site(site_id):
    site = db.session.get(Site, site_id)
    if not site:
        return error("Site introuvable", 404)
    data = request.get_json(silent=True) or {}
    for f in FIELDS:
        if f in data:
            setattr(site, f, data[f])
    db.session.commit()
    return success(site.to_dict())


@bp.delete("/<int:site_id>")
@role_required("Admin")
def delete_site(site_id):
    site = db.session.get(Site, site_id)
    if not site:
        return error("Site introuvable", 404)
    db.session.delete(site)
    db.session.commit()
    return success(message="Supprimé")
