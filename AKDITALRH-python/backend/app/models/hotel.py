"""Modèles Hotel, Chambre, HotelPrice."""
from app.extensions import db


class Hotel(db.Model):
    __tablename__ = "hotels"

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    hotel = db.Column(db.String(255))
    nom = db.Column(db.String(255))
    etoile = db.Column(db.String(45))
    region = db.Column(db.String(255))
    ville_id = db.Column(db.Integer, nullable=False)
    adresse = db.Column(db.String(255))
    images = db.Column(db.Text)
    mail = db.Column(db.Text)
    telephone = db.Column(db.String(45))
    nom_responsable = db.Column(db.String(255))
    reglement = db.Column(db.Text)
    created = db.Column(db.DateTime)

    # Cake : hasMany Chambre 'dependent' => false — supprimer un hôtel ne touche pas aux chambres
    chambres = db.relationship(
        "Chambre", back_populates="hotel", lazy="dynamic", passive_deletes="all"
    )

    def to_dict(self, with_chambres=False):
        data = {
            "id": self.id,
            "hotel": self.hotel,
            "nom": self.nom,
            "etoile": self.etoile,
            "region": self.region,
            "ville_id": self.ville_id,
            "adresse": self.adresse,
            "images": self.images,
            "mail": self.mail,
            "telephone": self.telephone,
            "nom_responsable": self.nom_responsable,
            "reglement": self.reglement,
            "created": self.created.isoformat() if self.created else None,
        }
        if with_chambres:
            data["chambres"] = [c.to_dict(with_prices=True) for c in self.chambres]
        return data


class Chambre(db.Model):
    __tablename__ = "chambres"

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    nom = db.Column(db.String(255))
    hotel_id = db.Column(db.Integer, db.ForeignKey("hotels.id"), nullable=False)
    type = db.Column(db.String(255))
    images = db.Column(db.String(255))
    created = db.Column(db.DateTime)

    hotel = db.relationship("Hotel", back_populates="chambres")
    prices = db.relationship(
        "HotelPrice", back_populates="chambre", lazy="dynamic", passive_deletes="all"
    )

    def to_dict(self, with_prices=False):
        data = {
            "id": self.id,
            "nom": self.nom,
            "hotel_id": self.hotel_id,
            "type": self.type,
            "images": self.images,
            "created": self.created.isoformat() if self.created else None,
        }
        if with_prices:
            data["prices"] = [p.to_dict() for p in self.prices]
        return data


class HotelPrice(db.Model):
    __tablename__ = "hotelprices"

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    chambre_id = db.Column(db.Integer, db.ForeignKey("chambres.id"), nullable=False)
    date_debut = db.Column(db.Date)
    date_fin = db.Column(db.Date)
    prix = db.Column(db.String(45))
    created = db.Column(db.DateTime)

    chambre = db.relationship("Chambre", back_populates="prices")

    def prix_numeric(self) -> float:
        try:
            return float(self.prix or 0)
        except (TypeError, ValueError):
            return 0.0

    def to_dict(self):
        return {
            "id": self.id,
            "chambre_id": self.chambre_id,
            "date_debut": self.date_debut.isoformat() if self.date_debut else None,
            "date_fin": self.date_fin.isoformat() if self.date_fin else None,
            "prix": self.prix,
            "created": self.created.isoformat() if self.created else None,
        }
