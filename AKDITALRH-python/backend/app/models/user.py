"""Modèle User."""
from app.extensions import db


class User(db.Model):
    __tablename__ = "users"

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    role_id = db.Column(db.Integer, db.ForeignKey("roles.id"), nullable=False)
    site_id = db.Column(db.Integer, nullable=False, default=0)
    username = db.Column(db.String(255))
    password = db.Column(db.String(255))
    totp_secret = db.Column(db.String(255))
    totp_enabled = db.Column(db.Boolean, nullable=False, default=False)
    etat = db.Column(db.String(50), default="Actif")
    nom = db.Column(db.String(255))
    prenom = db.Column(db.String(255))
    poste = db.Column(db.String(255))
    direction = db.Column(db.String(255))
    frequence_mobilite = db.Column(db.String(255))
    created = db.Column(db.DateTime)
    code = db.Column(db.String(255))
    tel = db.Column(db.String(20))
    statut = db.Column(db.String(255))

    role = db.relationship("Role", back_populates="users")

    def to_dict(self, include_sensitive=False):
        data = {
            "id": self.id,
            "role_id": self.role_id,
            "site_id": self.site_id,
            "username": self.username,
            "totp_enabled": bool(self.totp_enabled),
            "etat": self.etat,
            "nom": self.nom,
            "prenom": self.prenom,
            "poste": self.poste,
            "direction": self.direction,
            "frequence_mobilite": self.frequence_mobilite,
            "created": self.created.isoformat() if self.created else None,
            "code": self.code,
            "tel": self.tel,
            "statut": self.statut,
            "role": self.role.to_dict() if self.role else None,
        }
        if include_sensitive:
            data["totp_secret"] = self.totp_secret
        return data
