"""Modèles parc : Voiture, DemandeVoiture, Referentiel."""
from app.extensions import db


class Voiture(db.Model):
    __tablename__ = "voitures"

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    user_id = db.Column(db.Integer)
    ste_location = db.Column(db.String(100))
    immatriculation = db.Column(db.String(50))
    marque = db.Column(db.String(100))
    modele = db.Column(db.String(255))
    valeur_locative_ht = db.Column(db.Numeric(10, 2))
    duree_mois = db.Column(db.Integer)
    km = db.Column(db.Integer)
    cout_km_suplm_ttc = db.Column(db.Numeric(10, 3))
    nbr_pneus = db.Column(db.Integer)
    service_link = db.Column(db.String(100))
    categorie_vr = db.Column(db.String(255))
    livraison = db.Column(db.Date)
    fin_contrat = db.Column(db.Date)
    km_actuel = db.Column(db.Integer)
    created = db.Column(db.DateTime)
    modified = db.Column(db.DateTime)

    def to_dict(self):
        return {
            "id": self.id,
            "user_id": self.user_id,
            "ste_location": self.ste_location,
            "immatriculation": self.immatriculation,
            "marque": self.marque,
            "modele": self.modele,
            "valeur_locative_ht": float(self.valeur_locative_ht) if self.valeur_locative_ht is not None else None,
            "duree_mois": self.duree_mois,
            "km": self.km,
            "cout_km_suplm_ttc": float(self.cout_km_suplm_ttc) if self.cout_km_suplm_ttc is not None else None,
            "nbr_pneus": self.nbr_pneus,
            "service_link": self.service_link,
            "categorie_vr": self.categorie_vr,
            "livraison": self.livraison.isoformat() if self.livraison else None,
            "fin_contrat": self.fin_contrat.isoformat() if self.fin_contrat else None,
            "km_actuel": self.km_actuel,
            "created": self.created.isoformat() if self.created else None,
            "modified": self.modified.isoformat() if self.modified else None,
        }


class DemandeVoiture(db.Model):
    __tablename__ = "demandevoitures"

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    user_id = db.Column(db.Integer, nullable=False)
    marque = db.Column(db.String(100))
    modele = db.Column(db.String(100))
    duree_mois = db.Column(db.Integer)
    service_link = db.Column(db.String(255))
    categorie_vr = db.Column(db.String(100))
    livraison = db.Column(db.Date)
    created = db.Column(db.DateTime)
    rh_id = db.Column(db.Integer)
    achat_id = db.Column(db.Integer)
    dgd_id = db.Column(db.Integer)
    status_demande = db.Column(db.String(255))
    date_validation_rh = db.Column(db.DateTime)
    date_validation_dgd = db.Column(db.DateTime)
    date_validation_achat = db.Column(db.DateTime)
    description = db.Column(db.Text)
    commentaire_dgd = db.Column(db.Text)
    commentaire_achat = db.Column(db.Text)
    commentaire_rh = db.Column(db.Text)
    devis = db.Column(db.Text)
    beneficiaire = db.Column(db.Integer)
    nv_plafond = db.Column(db.Numeric(10, 2))
    nbr_pneus = db.Column(db.Integer)
    km = db.Column(db.Integer)

    def to_dict(self):
        return {
            "id": self.id,
            "user_id": self.user_id,
            "marque": self.marque,
            "modele": self.modele,
            "duree_mois": self.duree_mois,
            "service_link": self.service_link,
            "categorie_vr": self.categorie_vr,
            "livraison": self.livraison.isoformat() if self.livraison else None,
            "created": self.created.isoformat() if self.created else None,
            "rh_id": self.rh_id,
            "achat_id": self.achat_id,
            "dgd_id": self.dgd_id,
            "status_demande": self.status_demande,
            "date_validation_rh": self.date_validation_rh.isoformat() if self.date_validation_rh else None,
            "date_validation_dgd": self.date_validation_dgd.isoformat() if self.date_validation_dgd else None,
            "date_validation_achat": self.date_validation_achat.isoformat() if self.date_validation_achat else None,
            "description": self.description,
            "commentaire_dgd": self.commentaire_dgd,
            "commentaire_achat": self.commentaire_achat,
            "commentaire_rh": self.commentaire_rh,
            "devis": self.devis,
            "beneficiaire": self.beneficiaire,
            "nv_plafond": float(self.nv_plafond) if self.nv_plafond is not None else None,
            "nbr_pneus": self.nbr_pneus,
            "km": self.km,
        }


class Referentiel(db.Model):
    __tablename__ = "referentiels"

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    modele = db.Column(db.String(150), nullable=False)
    duree = db.Column(db.String(50), nullable=False)
    kilometrage = db.Column(db.String(50), nullable=False)
    loyer_ht_ald = db.Column(db.Numeric(10, 2))
    loyer_ttc_ald = db.Column(db.Numeric(10, 2))
    km_supp_ht_ald = db.Column(db.Numeric(10, 4))
    nb_pneus_ald = db.Column(db.Integer)
    cat_remplacement_ald = db.Column(db.String(150))
    loyer_ht_arval = db.Column(db.Numeric(10, 2))
    loyer_ttc_arval = db.Column(db.Numeric(10, 2))
    km_supp_ht_arval = db.Column(db.Numeric(10, 4))
    nb_pneus_arval = db.Column(db.Integer)
    cat_remplacement_arval = db.Column(db.String(150))
    moyenne_offres_ttc = db.Column(db.Numeric(10, 2))
    valeur_actuelle_moy_ttc = db.Column(db.Numeric(10, 2))
    augmentation_pourcent = db.Column(db.String(20))
    categorie = db.Column(db.String(150))

    def to_dict(self):
        def f(v):
            return float(v) if v is not None else None

        return {
            "id": self.id,
            "modele": self.modele,
            "duree": self.duree,
            "kilometrage": self.kilometrage,
            "loyer_ht_ald": f(self.loyer_ht_ald),
            "loyer_ttc_ald": f(self.loyer_ttc_ald),
            "km_supp_ht_ald": f(self.km_supp_ht_ald),
            "nb_pneus_ald": self.nb_pneus_ald,
            "cat_remplacement_ald": self.cat_remplacement_ald,
            "loyer_ht_arval": f(self.loyer_ht_arval),
            "loyer_ttc_arval": f(self.loyer_ttc_arval),
            "km_supp_ht_arval": f(self.km_supp_ht_arval),
            "nb_pneus_arval": self.nb_pneus_arval,
            "cat_remplacement_arval": self.cat_remplacement_arval,
            "moyenne_offres_ttc": f(self.moyenne_offres_ttc),
            "valeur_actuelle_moy_ttc": f(self.valeur_actuelle_moy_ttc),
            "augmentation_pourcent": self.augmentation_pourcent,
            "categorie": self.categorie,
        }
