"""CRUD Réservations hôtelières."""
from datetime import datetime

from flask import Blueprint, g, request

from app.extensions import db
from app.models.hotel import Chambre, Hotel
from app.models.reservation import Reservation
from app.schemas.serializers import parse_date
from app.services import mail_service, permission_service, token_service
from app.utils.decorators import jwt_required
from app.utils.responses import error, success

bp = Blueprint("reservations", __name__)

FIELDS = [
    "chambre_id", "site_id", "checkin", "checkout", "num_odm",
    "ordre_mission", "cin", "message", "confirmation", "etat",
    "image_repense", "reponse",
]


@bp.get("")
@jwt_required
def list_reservations():
    if not permission_service.can(g.current_user, "reservations", "read"):
        return error("Accès refusé", 403)
    q = Reservation.query
    if not permission_service.is_admin(g.current_user):
        # Non-admin : ses propres résas sauf rôles RH/Achat élevés
        role = permission_service.role_name(g.current_user)
        if role not in {"DRH", "RH", "Achat", "DRACHAT", "DGD"}:
            q = q.filter_by(user_id=g.current_user.id)
    etat = request.args.get("etat")
    if etat:
        q = q.filter_by(etat=etat)
    return success([r.to_dict() for r in q.order_by(Reservation.id.desc()).all()])


@bp.get("/<int:resa_id>")
@jwt_required
def get_reservation(resa_id):
    resa = db.session.get(Reservation, resa_id)
    if not resa:
        return error("Réservation introuvable", 404)
    if (
        not permission_service.is_admin(g.current_user)
        and resa.user_id != g.current_user.id
        and not permission_service.can(g.current_user, "reservations", "read")
    ):
        return error("Accès refusé", 403)
    return success(resa.to_dict())


@bp.post("")
@jwt_required
def create_reservation():
    if not permission_service.can(g.current_user, "reservations", "write"):
        return error("Accès refusé", 403)
    data = request.get_json(silent=True) or {}
    if not data.get("chambre_id") or not data.get("site_id"):
        return error("chambre_id et site_id requis", 400)

    resa = Reservation(
        user_id=g.current_user.id,
        chambre_id=data["chambre_id"],
        site_id=data["site_id"],
        checkin=parse_date(data.get("checkin")),
        checkout=parse_date(data.get("checkout")),
        num_odm=data.get("num_odm"),
        ordre_mission=data.get("ordre_mission"),
        cin=data.get("cin"),
        message=data.get("message"),
        etat="En cours",
        created=datetime.utcnow(),
    )
    db.session.add(resa)
    db.session.commit()

    # Email hôtel avec liens signés
    chambre = db.session.get(Chambre, resa.chambre_id)
    hotel = db.session.get(Hotel, chambre.hotel_id) if chambre else None
    if hotel and hotel.mail:
        token = token_service.make_token(
            {"rid": resa.id, "action": "respond"},
            salt=token_service.SALT_RESERVATION,
        )
        body = (
            f"Nouvelle réservation #{resa.id}\n"
            f"Accept: /api/v1/public/reservations/{token}/accept\n"
            f"Reject: /api/v1/public/reservations/{token}/reject\n"
        )
        mail_service.send_mail(
            subject=f"Réservation Akdital #{resa.id}",
            recipients=mail_service.parse_multi_emails(hotel.mail),
            body=body,
        )

    return success(resa.to_dict(), status=201)


@bp.patch("/<int:resa_id>")
@jwt_required
def update_reservation(resa_id):
    resa = db.session.get(Reservation, resa_id)
    if not resa:
        return error("Réservation introuvable", 404)
    if not permission_service.is_admin(g.current_user) and resa.user_id != g.current_user.id:
        if not permission_service.can(g.current_user, "reservations", "write"):
            return error("Accès refusé", 403)
    data = request.get_json(silent=True) or {}
    for f in FIELDS:
        if f not in data:
            continue
        if f in ("checkin", "checkout"):
            setattr(resa, f, parse_date(data[f]))
        else:
            setattr(resa, f, data[f])
    db.session.commit()
    return success(resa.to_dict())


@bp.delete("/<int:resa_id>")
@jwt_required
def delete_reservation(resa_id):
    resa = db.session.get(Reservation, resa_id)
    if not resa:
        return error("Réservation introuvable", 404)
    if not permission_service.is_admin(g.current_user) and resa.user_id != g.current_user.id:
        return error("Accès refusé", 403)
    db.session.delete(resa)
    db.session.commit()
    return success(message="Supprimé")
