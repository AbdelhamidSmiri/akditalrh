"""Modèles Appartement et Beneficiaire."""
from app.extensions import db


class Appartement(db.Model):
    __tablename__ = "appartements"

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    ville_id = db.Column(db.Integer)
    nom = db.Column(db.String(255))
    sexe = db.Column(db.String(255))
    capacite = db.Column(db.Integer)
    adresse = db.Column(db.String(255))
    images = db.Column(db.Text)
    created = db.Column(db.DateTime)

    def to_dict(self):
        return {
            "id": self.id,
            "ville_id": self.ville_id,
            "nom": self.nom,
            "sexe": self.sexe,
            "capacite": self.capacite,
            "adresse": self.adresse,
            "images": self.images,
            "created": self.created.isoformat() if self.created else None,
        }


class Beneficiaire(db.Model):
    __tablename__ = "beneficiaires"

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    site_id = db.Column(db.Integer)
    user_id = db.Column(db.Integer)
    appartement_id = db.Column(db.Integer)
    nom = db.Column(db.String(255))
    sexe = db.Column(db.String(255))
    mail = db.Column(db.String(255))
    telephone = db.Column(db.String(20))
    status = db.Column(db.String(20))
    etat = db.Column(db.String(255), default="En cours")
    date_debut = db.Column(db.Date)
    date_fin = db.Column(db.Date)
    note = db.Column(db.Text)
    created = db.Column(db.DateTime)

    # États Cake : En cours → Valider → Checkin → Checkout | Annuler
    TRANSITIONS = {
        "En cours": ["Valider", "Annuler"],
        "Valider": ["Checkin", "Annuler"],
        "Checkin": ["Checkout"],
        "Checkout": [],
        "Annuler": [],
    }

    def to_dict(self):
        return {
            "id": self.id,
            "site_id": self.site_id,
            "user_id": self.user_id,
            "appartement_id": self.appartement_id,
            "nom": self.nom,
            "sexe": self.sexe,
            "mail": self.mail,
            "telephone": self.telephone,
            "status": self.status,
            "etat": self.etat,
            "date_debut": self.date_debut.isoformat() if self.date_debut else None,
            "date_fin": self.date_fin.isoformat() if self.date_fin else None,
            "note": self.note,
            "created": self.created.isoformat() if self.created else None,
        }
