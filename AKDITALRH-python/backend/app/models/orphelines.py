"""Tables orphelines optionnelles : RhDemande, Commentaire, DemandeApprt, Demande."""
from app.extensions import db


class RhDemande(db.Model):
    __tablename__ = "rhdemandes"

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    user_id = db.Column(db.Integer)
    cree_par = db.Column(db.Integer)
    rh_id = db.Column(db.Integer)
    date_validation_rh = db.Column(db.DateTime)
    achat_id = db.Column(db.Integer)
    date_validation_achat = db.Column(db.DateTime)
    type = db.Column(db.String(255))
    description = db.Column(db.Text)
    images = db.Column(db.Text)
    created = db.Column(db.DateTime)

    def to_dict(self):
        return {
            "id": self.id,
            "user_id": self.user_id,
            "cree_par": self.cree_par,
            "rh_id": self.rh_id,
            "date_validation_rh": self.date_validation_rh.isoformat() if self.date_validation_rh else None,
            "achat_id": self.achat_id,
            "date_validation_achat": self.date_validation_achat.isoformat() if self.date_validation_achat else None,
            "type": self.type,
            "description": self.description,
            "images": self.images,
            "created": self.created.isoformat() if self.created else None,
        }


class Commentaire(db.Model):
    __tablename__ = "commentaires"

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    user_id = db.Column(db.Integer)
    rhdemande_id = db.Column(db.Integer)
    commentaire = db.Column(db.Text)
    images = db.Column(db.Text)
    created = db.Column(db.DateTime)

    def to_dict(self):
        return {
            "id": self.id,
            "user_id": self.user_id,
            "rhdemande_id": self.rhdemande_id,
            "commentaire": self.commentaire,
            "images": self.images,
            "created": self.created.isoformat() if self.created else None,
        }


class DemandeApprt(db.Model):
    __tablename__ = "demandeapprts"

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    user_id = db.Column(db.Integer)
    ville_id = db.Column(db.Integer)
    secteur = db.Column(db.String(255))
    nb_place = db.Column(db.String(255))
    genre = db.Column(db.String(255))
    etat = db.Column(db.String(255), default="En cours")
    note = db.Column(db.Text)
    created = db.Column(db.DateTime)

    def to_dict(self):
        return {
            "id": self.id,
            "user_id": self.user_id,
            "ville_id": self.ville_id,
            "secteur": self.secteur,
            "nb_place": self.nb_place,
            "genre": self.genre,
            "etat": self.etat,
            "note": self.note,
            "created": self.created.isoformat() if self.created else None,
        }


class Demande(db.Model):
    """Demande plafond carburant."""

    __tablename__ = "demandes"

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    user_id = db.Column(db.Integer, nullable=False)
    site_id = db.Column(db.Integer, nullable=False)
    nv_plafond = db.Column(db.Numeric(10, 2))
    type_mt = db.Column(db.String(50))
    status = db.Column(db.String(255))
    created = db.Column(db.DateTime)
    rh_id = db.Column(db.Integer)
    achat_id = db.Column(db.Integer)
    status_demande = db.Column(db.Integer, default=0)
    date_validation_rh = db.Column(db.DateTime)
    date_validation_achat = db.Column(db.DateTime)
    description = db.Column(db.Text)
    commentaire_rh = db.Column(db.Text, nullable=False, default="")
    commentaire_achat = db.Column(db.Text, nullable=False, default="")
    cree_par = db.Column(db.Integer)

    def to_dict(self):
        return {
            "id": self.id,
            "user_id": self.user_id,
            "site_id": self.site_id,
            "nv_plafond": float(self.nv_plafond) if self.nv_plafond is not None else None,
            "type_mt": self.type_mt,
            "status": self.status,
            "created": self.created.isoformat() if self.created else None,
            "rh_id": self.rh_id,
            "achat_id": self.achat_id,
            "status_demande": self.status_demande,
            "date_validation_rh": self.date_validation_rh.isoformat() if self.date_validation_rh else None,
            "date_validation_achat": self.date_validation_achat.isoformat() if self.date_validation_achat else None,
            "description": self.description,
            "commentaire_rh": self.commentaire_rh,
            "commentaire_achat": self.commentaire_achat,
            "cree_par": self.cree_par,
        }
