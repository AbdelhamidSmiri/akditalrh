"""Modèle Reservation hôtel."""
from app.extensions import db


class Reservation(db.Model):
    __tablename__ = "reservations"

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    user_id = db.Column(db.Integer, nullable=False)
    chambre_id = db.Column(db.Integer, nullable=False)
    site_id = db.Column(db.Integer, nullable=False)
    checkin = db.Column(db.Date)
    checkout = db.Column(db.Date)
    num_odm = db.Column(db.String(45))
    ordre_mission = db.Column(db.String(255))
    cin = db.Column(db.String(255))
    message = db.Column(db.Text)
    created = db.Column(db.DateTime)
    confirmation = db.Column(db.String(255))
    etat = db.Column(db.String(45), default="En cours")
    image_repense = db.Column(db.String(255), default="En cours")
    reponse = db.Column(db.Text)
    date_reponse = db.Column(db.DateTime)

    def to_dict(self, with_relations=True):
        data = {
            "id": self.id,
            "user_id": self.user_id,
            "chambre_id": self.chambre_id,
            "site_id": self.site_id,
            "checkin": self.checkin.isoformat() if self.checkin else None,
            "checkout": self.checkout.isoformat() if self.checkout else None,
            "num_odm": self.num_odm,
            "ordre_mission": self.ordre_mission,
            "cin": self.cin,
            "message": self.message,
            "created": self.created.isoformat() if self.created else None,
            "confirmation": self.confirmation,
            "etat": self.etat,
            "image_repense": self.image_repense,
            "reponse": self.reponse,
            "date_reponse": self.date_reponse.isoformat() if self.date_reponse else None,
        }
        if with_relations:
            from app.models.hotel import Chambre, Hotel
            from app.models.user import User

            chambre = db.session.get(Chambre, self.chambre_id)
            hotel = db.session.get(Hotel, chambre.hotel_id) if chambre else None
            user = db.session.get(User, self.user_id)
            data["chambre"] = chambre.to_dict() if chambre else None
            data["hotel"] = hotel.to_dict() if hotel else None
            data["hotel_nom"] = (hotel.hotel or hotel.nom) if hotel else None
            data["user"] = (
                {"id": user.id, "nom": user.nom, "prenom": user.prenom, "username": user.username}
                if user
                else None
            )
            if data["chambre"] and hotel:
                data["chambre"]["hotel"] = hotel.to_dict()
        return data
