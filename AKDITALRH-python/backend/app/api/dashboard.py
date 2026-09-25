"""Dashboard stats."""
from flask import Blueprint, g

from app.models.carburant import CarteCarburant
from app.models.jawaz import TagJawaz
from app.models.logement import Appartement, Beneficiaire
from app.models.parc import DemandeVoiture, Voiture
from app.models.reservation import Reservation
from app.models.user import User
from app.models.volreservation import VolReservation
from app.services import permission_service
from app.utils.decorators import jwt_required
from app.utils.responses import error, success

bp = Blueprint("dashboard", __name__)


@bp.get("/stats")
@jwt_required
def stats():
    if not permission_service.can(g.current_user, "dashboard", "read"):
        return error("Accès refusé", 403)

    user = g.current_user
    is_admin = permission_service.is_admin(user)

    def count(model, **filters):
        q = model.query
        for k, v in filters.items():
            q = q.filter_by(**{k: v})
        return q.count()

    data = {
        "reservations_en_cours": count(Reservation, etat="En cours"),
        "vols_en_cours": count(VolReservation, etat="En cours"),
        "voitures": count(Voiture),
        "cartes_carburant": count(CarteCarburant),
        "tag_jawaz": count(TagJawaz),
        "appartements": count(Appartement),
        "beneficiaires_en_cours": count(Beneficiaire, etat="En cours"),
        "demandes_voitures": count(DemandeVoiture),
    }
    if is_admin:
        data["users"] = count(User)
        data["users_actifs"] = User.query.filter(User.etat.ilike("actif")).count()

    # Stats personnelles
    data["mes_reservations"] = count(Reservation, user_id=user.id)
    data["mes_vols"] = count(VolReservation, user_id=user.id)

    return success(data)
