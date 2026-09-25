"""Modèle Site (clinique)."""
from app.extensions import db


class Site(db.Model):
    __tablename__ = "sites"

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    ville_id = db.Column(db.Integer)
    site = db.Column(db.String(255))
    adresse = db.Column(db.String(255))
    telephone = db.Column(db.String(255))
    mail = db.Column(db.String(255))
    created = db.Column(db.DateTime)

    def to_dict(self):
        data = {
            "id": self.id,
            "ville_id": self.ville_id,
            "site": self.site,
            "adresse": self.adresse,
            "telephone": self.telephone,
            "mail": self.mail,
            "created": self.created.isoformat() if self.created else None,
        }
        if self.ville_id:
            from app.models.ville import Ville

            ville = db.session.get(Ville, self.ville_id)
            if ville:
                data["ville"] = ville.to_dict()
        return data
