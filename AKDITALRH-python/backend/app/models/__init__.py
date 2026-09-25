"""Export de tous les modèles SQLAlchemy."""
from app.models.carburant import CarteCarburant
from app.models.hotel import Chambre, Hotel, HotelPrice
from app.models.jawaz import TagJawaz
from app.models.logement import Appartement, Beneficiaire
from app.models.orphelines import Commentaire, Demande, DemandeApprt, RhDemande
from app.models.parc import DemandeVoiture, Referentiel, Voiture
from app.models.reservation import Reservation
from app.models.role import Role
from app.models.site import Site
from app.models.user import User
from app.models.ville import Ville
from app.models.volreservation import VolReservation

__all__ = [
    "User",
    "Role",
    "Site",
    "Ville",
    "Hotel",
    "Chambre",
    "HotelPrice",
    "Reservation",
    "VolReservation",
    "Appartement",
    "Beneficiaire",
    "Voiture",
    "DemandeVoiture",
    "Referentiel",
    "CarteCarburant",
    "TagJawaz",
    "RhDemande",
    "Commentaire",
    "DemandeApprt",
    "Demande",
]
