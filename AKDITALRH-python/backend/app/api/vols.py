"""CRUD Vols + actions agence."""
from datetime import datetime

from flask import Blueprint, g, request

from app.extensions import db
from app.models.volreservation import VolReservation
from app.schemas.serializers import parse_date, parse_datetime
from app.services import permission_service
from app.utils.decorators import jwt_required, role_required
from app.utils.responses import error, success

bp = Blueprint("vols", __name__)

FIELDS = [
    "site_id", "depart", "destination", "date_aller", "date_retour",
    "num_odm", "ordre_mission", "cin", "passport", "message", "etat",
    "documents", "reponse", "num_vol", "file_aller", "file_retour",
    "transfer", "nom_transfer", "date_transfer", "tel_transfer",
    "description_transfer", "prix_vol", "prix_transfert", "pick_up",
    "transfer_destination", "date_aller1", "date_aller2",
    "date_retour1", "date_retour2",
]


@bp.get("")
@jwt_required
def list_vols():
    if not permission_service.can(g.current_user, "vols", "read"):
        return error("Accès refusé", 403)
    q = VolReservation.query
    if not permission_service.is_admin(g.current_user) and not permission_service.is_agence(g.current_user):
        role = permission_service.role_name(g.current_user)
        if role not in {"DRH", "RH", "Achat", "DGD"}:
            q = q.filter_by(user_id=g.current_user.id)
    etat = request.args.get("etat")
    if etat:
        q = q.filter_by(etat=etat)
    return success([v.to_dict() for v in q.order_by(VolReservation.id.desc()).all()])


@bp.get("/agence")
@role_required("Agence")
def list_agence():
    etat = request.args.get("etat", "En cours")
    q = VolReservation.query
    if etat:
        q = q.filter_by(etat=etat)
    return success([v.to_dict() for v in q.order_by(VolReservation.id.desc()).all()])


@bp.get("/<int:vol_id>")
@jwt_required
def get_vol(vol_id):
    vol = db.session.get(VolReservation, vol_id)
    if not vol:
        return error("Vol introuvable", 404)
    if (
        not permission_service.is_admin(g.current_user)
        and not permission_service.is_agence(g.current_user)
        and vol.user_id != g.current_user.id
    ):
        return error("Accès refusé", 403)
    return success(vol.to_dict())


@bp.post("")
@jwt_required
def create_vol():
    if not permission_service.can(g.current_user, "vols", "write"):
        return error("Accès refusé", 403)
    data = request.get_json(silent=True) or {}
    vol = VolReservation(
        user_id=g.current_user.id,
        site_id=data.get("site_id") or g.current_user.site_id or 0,
        depart=data.get("depart"),
        destination=data.get("destination"),
        date_aller=parse_date(data.get("date_aller")),
        date_retour=parse_date(data.get("date_retour")),
        num_odm=data.get("num_odm"),
        ordre_mission=data.get("ordre_mission"),
        cin=data.get("cin"),
        passport=data.get("passport"),
        message=data.get("message"),
        etat="En cours",
        transfer=str(data.get("transfer", "0")),
        created=datetime.utcnow(),
    )
    db.session.add(vol)
    db.session.commit()
    return success(vol.to_dict(), status=201)


@bp.patch("/<int:vol_id>")
@jwt_required
def update_vol(vol_id):
    vol = db.session.get(VolReservation, vol_id)
    if not vol:
        return error("Vol introuvable", 404)
    if not permission_service.is_admin(g.current_user) and vol.user_id != g.current_user.id:
        if not permission_service.can(g.current_user, "vols", "write"):
            return error("Accès refusé", 403)
    data = request.get_json(silent=True) or {}
    for f in FIELDS:
        if f not in data:
            continue
        if f in ("date_aller", "date_retour"):
            setattr(vol, f, parse_date(data[f]))
        elif f == "date_transfer":
            setattr(vol, f, parse_datetime(data[f]))
        else:
            setattr(vol, f, data[f])
    db.session.commit()
    return success(vol.to_dict())


@bp.delete("/<int:vol_id>")
@jwt_required
def delete_vol(vol_id):
    vol = db.session.get(VolReservation, vol_id)
    if not vol:
        return error("Vol introuvable", 404)
    if not permission_service.is_admin(g.current_user) and vol.user_id != g.current_user.id:
        return error("Accès refusé", 403)
    db.session.delete(vol)
    db.session.commit()
    return success(message="Supprimé")


@bp.post("/<int:vol_id>/valider")
@role_required("Agence")
def valider_vol(vol_id):
    vol = db.session.get(VolReservation, vol_id)
    if not vol:
        return error("Vol introuvable", 404)
    data = request.get_json(silent=True) or {}
    vol.etat = "Validé"
    vol.num_vol = data.get("num_vol", vol.num_vol)
    vol.file_aller = data.get("file_aller", vol.file_aller)
    vol.file_retour = data.get("file_retour", vol.file_retour)
    vol.prix_vol = str(data.get("prix_vol", vol.prix_vol or "0"))
    vol.prix_transfert = str(data.get("prix_transfert", vol.prix_transfert or "0"))
    vol.reponse = data.get("reponse", vol.reponse)
    vol.date_reponse = datetime.utcnow()
    if "transfer" in data:
        vol.transfer = str(data["transfer"])
    for f in ("nom_transfer", "tel_transfer", "description_transfer", "pick_up", "transfer_destination"):
        if f in data:
            setattr(vol, f, data[f])
    if "date_transfer" in data:
        vol.date_transfer = parse_datetime(data["date_transfer"])
    db.session.commit()
    return success(vol.to_dict(), message="Vol validé")


@bp.post("/<int:vol_id>/annuler")
@role_required("Agence")
def annuler_vol(vol_id):
    vol = db.session.get(VolReservation, vol_id)
    if not vol:
        return error("Vol introuvable", 404)
    data = request.get_json(silent=True) or {}
    vol.etat = "Annulé"
    vol.reponse = data.get("reponse") or "Annulé"
    vol.date_reponse = datetime.utcnow()
    db.session.commit()
    return success(vol.to_dict(), message="Vol annulé")


@bp.post("/<int:vol_id>/archive")
@role_required("Agence")
def archive_vol(vol_id):
    vol = db.session.get(VolReservation, vol_id)
    if not vol:
        return error("Vol introuvable", 404)
    vol.etat = "Archivé"
    vol.date_reponse = datetime.utcnow()
    db.session.commit()
    return success(vol.to_dict(), message="Vol archivé")
