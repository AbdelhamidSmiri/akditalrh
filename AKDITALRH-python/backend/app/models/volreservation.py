"""Modèle Volreservation."""
from app.extensions import db


class VolReservation(db.Model):
    __tablename__ = "volreservations"

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    user_id = db.Column(db.Integer, nullable=False)
    site_id = db.Column(db.Integer, nullable=False)
    depart = db.Column(db.String(255))
    destination = db.Column(db.String(255))
    date_aller = db.Column(db.Date)
    date_retour = db.Column(db.Date)
    num_odm = db.Column(db.String(45))
    ordre_mission = db.Column(db.String(255))
    cin = db.Column(db.String(255))
    passport = db.Column(db.String(255))
    message = db.Column(db.Text)
    created = db.Column(db.DateTime)
    etat = db.Column(db.String(45), default="En cours")
    documents = db.Column(db.Text)
    reponse = db.Column(db.Text)
    date_reponse = db.Column(db.DateTime)
    num_vol = db.Column(db.String(255))
    file_aller = db.Column(db.String(255))
    file_retour = db.Column(db.String(255))
    transfer = db.Column(db.String(255), nullable=False, default="0")
    nom_transfer = db.Column(db.String(45))
    date_transfer = db.Column(db.DateTime)
    tel_transfer = db.Column(db.String(45))
    description_transfer = db.Column(db.Text)
    prix_vol = db.Column(db.String(50), default="0")
    prix_transfert = db.Column(db.String(50), default="0")
    pick_up = db.Column(db.String(255))
    transfer_destination = db.Column(db.String(255))
    date_aller1 = db.Column(db.String(255))
    date_aller2 = db.Column(db.String(255))
    date_retour1 = db.Column(db.String(255))
    date_retour2 = db.Column(db.String(255))

    def to_dict(self, with_user=True):
        data = {
            "id": self.id,
            "user_id": self.user_id,
            "site_id": self.site_id,
            "depart": self.depart,
            "origine": self.depart,
            "destination": self.destination,
            "date_aller": self.date_aller.isoformat() if self.date_aller else None,
            "date_depart": self.date_aller.isoformat() if self.date_aller else None,
            "date_retour": self.date_retour.isoformat() if self.date_retour else None,
            "num_odm": self.num_odm,
            "ordre_mission": self.ordre_mission,
            "cin": self.cin,
            "passport": self.passport,
            "message": self.message,
            "created": self.created.isoformat() if self.created else None,
            "etat": self.etat,
            "statut": self.etat,
            "documents": self.documents,
            "reponse": self.reponse,
            "date_reponse": self.date_reponse.isoformat() if self.date_reponse else None,
            "num_vol": self.num_vol,
            "file_aller": self.file_aller,
            "file_retour": self.file_retour,
            "transfer": self.transfer,
            "nom_transfer": self.nom_transfer,
            "date_transfer": self.date_transfer.isoformat() if self.date_transfer else None,
            "tel_transfer": self.tel_transfer,
            "description_transfer": self.description_transfer,
            "prix_vol": self.prix_vol,
            "prix_transfert": self.prix_transfert,
            "pick_up": self.pick_up,
            "transfer_destination": self.transfer_destination,
            "date_aller1": self.date_aller1,
            "date_aller2": self.date_aller2,
            "date_retour1": self.date_retour1,
            "date_retour2": self.date_retour2,
            "nom": None,
            "prenom": None,
            "user": None,
        }
        if with_user and self.user_id:
            from app.models.user import User

            user = db.session.get(User, self.user_id)
            if user:
                data["nom"] = user.nom
                data["prenom"] = user.prenom
                data["user"] = {
                    "id": user.id,
                    "nom": user.nom,
                    "prenom": user.prenom,
                    "username": user.username,
                }
        if self.site_id:
            from app.models.site import Site

            site = db.session.get(Site, self.site_id)
            if site:
                data["site"] = site.to_dict()
                data["site_nom"] = site.site
        return data
