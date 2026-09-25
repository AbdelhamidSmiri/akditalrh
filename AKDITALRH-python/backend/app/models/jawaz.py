"""Modèle TagJawaz."""
from app.extensions import db


class TagJawaz(db.Model):
    __tablename__ = "tagjawazs"

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    user_id = db.Column(db.Integer, nullable=False)
    site_id = db.Column(db.Integer, nullable=False)
    ref_jawaz = db.Column(db.String(50), nullable=False)

    def to_dict(self):
        return {
            "id": self.id,
            "user_id": self.user_id,
            "site_id": self.site_id,
            "ref_jawaz": self.ref_jawaz,
        }
