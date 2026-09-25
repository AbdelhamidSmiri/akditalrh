"""Modèle Ville."""
from app.extensions import db


class Ville(db.Model):
    __tablename__ = "villes"

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    ville = db.Column(db.String(255))

    def to_dict(self):
        return {"id": self.id, "ville": self.ville}
