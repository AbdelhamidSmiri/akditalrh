"""Modèle Role."""
from app.extensions import db


class Role(db.Model):
    __tablename__ = "roles"

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    role = db.Column(db.String(255))
    plafond_hotel = db.Column(db.String(255))

    users = db.relationship("User", back_populates="role", lazy="dynamic")

    def to_dict(self):
        return {
            "id": self.id,
            "role": self.role,
            "plafond_hotel": self.plafond_hotel,
        }

    def plafond_numeric(self) -> float:
        try:
            return float(self.plafond_hotel or 0)
        except (TypeError, ValueError):
            return 0.0
