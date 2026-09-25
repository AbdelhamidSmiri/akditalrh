"""Enregistrement des blueprints API."""
from flask import Blueprint

from app.api import (
    auth,
    carburant,
    dashboard,
    files,
    hotels,
    jawaz,
    logement,
    parc,
    public,
    reservations,
    roles,
    sites,
    users,
    villes,
    vols,
)


def register_blueprints(app):
    api = Blueprint("api_v1", __name__)

    @api.get("/health")
    def health():
        return {"ok": True, "status": "healthy", "service": "akditalrh-api"}

    api.register_blueprint(auth.bp, url_prefix="/auth")
    api.register_blueprint(dashboard.bp, url_prefix="/dashboard")
    api.register_blueprint(users.bp, url_prefix="/users")
    api.register_blueprint(roles.bp, url_prefix="/roles")
    api.register_blueprint(sites.bp, url_prefix="/sites")
    api.register_blueprint(villes.bp, url_prefix="/villes")
    api.register_blueprint(hotels.bp, url_prefix="/hotels")
    api.register_blueprint(reservations.bp, url_prefix="/reservations")
    api.register_blueprint(public.bp, url_prefix="/public")
    api.register_blueprint(vols.bp, url_prefix="/vols")
    api.register_blueprint(logement.bp, url_prefix="")
    # Routes top-level : /voitures, /demande-voitures, /referentiels, /parc/requetes
    api.register_blueprint(parc.bp, url_prefix="")
    api.register_blueprint(carburant.bp, url_prefix="/cartes-carburant")
    api.register_blueprint(jawaz.bp, url_prefix="/tag-jawaz")
    api.register_blueprint(files.bp, url_prefix="/files")

    app.register_blueprint(api, url_prefix="/api/v1")
