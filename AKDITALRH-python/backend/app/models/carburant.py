"""Modèle CarteCarburant."""
from app.extensions import db


class CarteCarburant(db.Model):
    __tablename__ = "cartecarburants"

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    user_id = db.Column(db.Integer, nullable=False)
    site_id = db.Column(db.Integer, nullable=False, default=0)
    prestataire = db.Column(db.String(100), nullable=False)
    identifiant = db.Column(db.String(50), nullable=False)
    carte_labelle = db.Column(db.String(100), nullable=False)
    utilisateur = db.Column(db.String(100), nullable=False)
    nv_plafond = db.Column(db.Numeric(10, 2))
    type_mt = db.Column(db.String(50))
    status = db.Column(db.String(50))
    created = db.Column(db.DateTime)
    modified = db.Column(db.DateTime)

    def to_dict(self):
        return {
            "id": self.id,
            "user_id": self.user_id,
            "site_id": self.site_id,
            "prestataire": self.prestataire,
            "identifiant": self.identifiant,
            "carte_labelle": self.carte_labelle,
            "utilisateur": self.utilisateur,
            "nv_plafond": float(self.nv_plafond) if self.nv_plafond is not None else None,
            "type_mt": self.type_mt,
            "status": self.status,
            "created": self.created.isoformat() if self.created else None,
            "modified": self.modified.isoformat() if self.modified else None,
        }
