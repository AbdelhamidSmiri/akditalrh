"""Endpoints publics (liens email signés)."""
from datetime import datetime

from flask import Blueprint, request

from app.extensions import db
from app.models.logement import Beneficiaire
from app.models.reservation import Reservation
from app.services import token_service
from app.utils.responses import error, success

bp = Blueprint("public", __name__)


def _load_reservation(token: str):
    payload = token_service.load_token(token, salt=token_service.SALT_RESERVATION)
    if not payload or "rid" not in payload:
        return None, error("Token invalide ou expiré", 400)
    resa = db.session.get(Reservation, payload["rid"])
    if not resa:
        return None, error("Réservation introuvable", 404)
    return resa, None


@bp.get("/reservations/<token>/accept")
def accept_get(token):
    resa, err = _load_reservation(token)
    if err:
        return err
    return success({"reservation": resa.to_dict(), "action": "accept"})


@bp.post("/reservations/<token>/accept")
def accept_post(token):
    resa, err = _load_reservation(token)
    if err:
        return err
    data = request.get_json(silent=True) or {}
    resa.etat = "Accepté"
    resa.reponse = data.get("reponse") or data.get("message") or "Accepté"
    resa.confirmation = data.get("confirmation")
    resa.image_repense = data.get("image_repense") or resa.image_repense
    resa.date_reponse = datetime.utcnow()
    db.session.commit()
    return success(resa.to_dict(), message="Réservation acceptée")


@bp.get("/reservations/<token>/reject")
def reject_get(token):
    resa, err = _load_reservation(token)
    if err:
        return err
    return success({"reservation": resa.to_dict(), "action": "reject"})


@bp.post("/reservations/<token>/reject")
def reject_post(token):
    resa, err = _load_reservation(token)
    if err:
        return err
    data = request.get_json(silent=True) or {}
    resa.etat = "Rejeté"
    resa.reponse = data.get("reponse") or data.get("message") or "Rejeté"
    resa.date_reponse = datetime.utcnow()
    db.session.commit()
    return success(resa.to_dict(), message="Réservation rejetée")


@bp.get("/beneficiaires/<token>/conditions")
def beneficiaire_conditions_get(token):
    payload = token_service.load_token(token, salt=token_service.SALT_BENEFICIAIRE)
    if not payload or "bid" not in payload:
        return error("Token invalide ou expiré", 400)
    ben = db.session.get(Beneficiaire, payload["bid"])
    if not ben:
        return error("Bénéficiaire introuvable", 404)
    return success({"beneficiaire": ben.to_dict(), "action": "conditions"})


@bp.post("/beneficiaires/<token>/conditions")
def beneficiaire_conditions_post(token):
    payload = token_service.load_token(token, salt=token_service.SALT_BENEFICIAIRE)
    if not payload or "bid" not in payload:
        return error("Token invalide ou expiré", 400)
    ben = db.session.get(Beneficiaire, payload["bid"])
    if not ben:
        return error("Bénéficiaire introuvable", 404)
    data = request.get_json(silent=True) or {}
    if data.get("accept"):
        allowed = Beneficiaire.TRANSITIONS.get(ben.etat or "En cours", [])
        if "Valider" in allowed:
            ben.etat = "Valider"
            db.session.commit()
    return success(ben.to_dict())
