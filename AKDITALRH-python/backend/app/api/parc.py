"""Parc : voitures, calendrier, import, demandes VR, référentiels, requêtes."""
from datetime import datetime

from flask import Blueprint, g, request

from app.extensions import db
from app.models.parc import DemandeVoiture, Referentiel, Voiture
from app.models.site import Site
from app.models.user import User
from app.schemas.serializers import parse_date, parse_datetime
from app.services import import_service, permission_service
from app.utils.decorators import jwt_required
from app.utils.responses import error, success

bp = Blueprint("parc", __name__)

VOITURE_FIELDS = [
    "user_id", "ste_location", "immatriculation", "marque", "modele",
    "valeur_locative_ht", "duree_mois", "km", "cout_km_suplm_ttc",
    "nbr_pneus", "service_link", "categorie_vr", "livraison",
    "fin_contrat", "km_actuel",
]


def _user_brief(user_id):
    if not user_id:
        return None
    u = db.session.get(User, user_id)
    if not u:
        return None
    site_name = None
    if u.site_id:
        site = db.session.get(Site, u.site_id)
        if site:
            site_name = site.site
    return {
        "id": u.id,
        "nom": u.nom,
        "prenom": u.prenom,
        "username": u.username,
        "site_id": u.site_id,
        "site": site_name,
    }


def _voiture_dict(v):
    data = v.to_dict()
    data["user"] = _user_brief(v.user_id)
    return data


def _demande_dict(d):
    data = d.to_dict()
    data["user"] = _user_brief(d.user_id)
    return data


# ── Voitures ──────────────────────────────────────────────────

@bp.get("/voitures")
@jwt_required
def list_voitures():
    if not permission_service.can(g.current_user, "parc", "read"):
        return error("Accès refusé", 403)
    return success([_voiture_dict(v) for v in Voiture.query.order_by(Voiture.id.desc()).all()])


@bp.get("/voitures/calendrier")
@jwt_required
def calendrier_voitures():
    """Événements FullCalendar : fin_contrat (comme Cake Voitures/calendrier)."""
    if not permission_service.can(g.current_user, "parc", "read"):
        return error("Accès refusé", 403)
    items = []
    for v in Voiture.query.all():
        user = _user_brief(v.user_id)
        items.append({
            "id": v.id,
            "immatriculation": v.immatriculation,
            "marque": v.marque,
            "modele": v.modele,
            "livraison": v.livraison.isoformat() if v.livraison else None,
            "fin_contrat": v.fin_contrat.isoformat() if v.fin_contrat else None,
            "date_debut": v.livraison.isoformat() if v.livraison else None,
            "date_fin": v.fin_contrat.isoformat() if v.fin_contrat else None,
            "user_id": v.user_id,
            "ste_location": v.ste_location,
            "km_actuel": v.km_actuel,
            "valeur_locative_ht": float(v.valeur_locative_ht) if v.valeur_locative_ht is not None else None,
            "duree_mois": v.duree_mois,
            "km": v.km,
            "cout_km_suplm_ttc": float(v.cout_km_suplm_ttc) if v.cout_km_suplm_ttc is not None else None,
            "user": user,
            "demandeur": " ".join(filter(None, [user.get("nom"), user.get("prenom")])).strip() if user else None,
            "site": user.get("site") if user else None,
        })
    return success(items)


@bp.get("/voitures/<int:vid>")
@jwt_required
def get_voiture(vid):
    if not permission_service.can(g.current_user, "parc", "read"):
        return error("Accès refusé", 403)
    v = db.session.get(Voiture, vid)
    if not v:
        return error("Voiture introuvable", 404)
    return success(_voiture_dict(v))


@bp.post("/voitures")
@jwt_required
def create_voiture():
    if not permission_service.can(g.current_user, "parc", "write"):
        return error("Accès refusé", 403)
    data = request.get_json(silent=True) or {}
    now = datetime.utcnow()
    v = Voiture(created=now, modified=now)
    for f in VOITURE_FIELDS:
        if f not in data:
            continue
        if f in ("livraison", "fin_contrat"):
            setattr(v, f, parse_date(data[f]))
        else:
            setattr(v, f, data[f])
    db.session.add(v)
    db.session.commit()
    return success(v.to_dict(), status=201)


@bp.patch("/voitures/<int:vid>")
@jwt_required
def update_voiture(vid):
    if not permission_service.can(g.current_user, "parc", "write"):
        return error("Accès refusé", 403)
    v = db.session.get(Voiture, vid)
    if not v:
        return error("Voiture introuvable", 404)
    data = request.get_json(silent=True) or {}
    for f in VOITURE_FIELDS:
        if f not in data:
            continue
        if f in ("livraison", "fin_contrat"):
            setattr(v, f, parse_date(data[f]))
        else:
            setattr(v, f, data[f])
    v.modified = datetime.utcnow()
    db.session.commit()
    return success(v.to_dict())


@bp.delete("/voitures/<int:vid>")
@jwt_required
def delete_voiture(vid):
    if not permission_service.can(g.current_user, "parc", "write"):
        return error("Accès refusé", 403)
    v = db.session.get(Voiture, vid)
    if not v:
        return error("Voiture introuvable", 404)
    db.session.delete(v)
    db.session.commit()
    return success(message="Supprimé")


@bp.post("/voitures/import")
@jwt_required
def import_voitures():
    if not permission_service.can(g.current_user, "parc", "write"):
        return error("Accès refusé", 403)
    f = request.files.get("file")
    if not f:
        return error("Fichier Excel requis (file)", 400)
    rows = import_service.parse_voitures(f)
    now = datetime.utcnow()
    created = 0
    for row in rows:
        v = Voiture(**row, created=now, modified=now)
        db.session.add(v)
        created += 1
    db.session.commit()
    return success({"imported": created}, status=201)


# ── Demandes voiture ──────────────────────────────────────────

@bp.get("/demande-voitures")
@jwt_required
def list_demandes():
    if not permission_service.can(g.current_user, "parc", "read"):
        return error("Accès refusé", 403)
    q = DemandeVoiture.query
    if not permission_service.is_admin(g.current_user):
        role = permission_service.role_name(g.current_user)
        if role not in {"RH", "DRH", "DGD", "Achat", "DRACHAT"}:
            q = q.filter_by(user_id=g.current_user.id)
    return success([_demande_dict(d) for d in q.order_by(DemandeVoiture.id.desc()).all()])


@bp.get("/demande-voitures/<int:did>")
@jwt_required
def get_demande(did):
    d = db.session.get(DemandeVoiture, did)
    if not d:
        return error("Demande introuvable", 404)
    return success(_demande_dict(d))


@bp.post("/demande-voitures")
@jwt_required
def create_demande():
    if not permission_service.can(g.current_user, "parc", "write") and not permission_service.can(
        g.current_user, "parc", "read"
    ):
        return error("Accès refusé", 403)
    data = request.get_json(silent=True) or {}
    d = DemandeVoiture(
        user_id=data.get("user_id") or g.current_user.id,
        marque=data.get("marque"),
        modele=data.get("modele"),
        duree_mois=data.get("duree_mois"),
        service_link=data.get("service_link"),
        categorie_vr=data.get("categorie_vr"),
        livraison=parse_date(data.get("livraison")),
        description=data.get("description"),
        beneficiaire=data.get("beneficiaire"),
        nv_plafond=data.get("nv_plafond"),
        nbr_pneus=data.get("nbr_pneus"),
        km=data.get("km"),
        devis=data.get("devis"),
        status_demande=data.get("status_demande") or "En attente RH",
        created=datetime.utcnow(),
    )
    db.session.add(d)
    db.session.commit()
    return success(d.to_dict(), status=201)


@bp.patch("/demande-voitures/<int:did>")
@jwt_required
def update_demande(did):
    d = db.session.get(DemandeVoiture, did)
    if not d:
        return error("Demande introuvable", 404)
    data = request.get_json(silent=True) or {}
    for f in (
        "marque", "modele", "duree_mois", "service_link", "categorie_vr",
        "description", "devis", "beneficiaire", "nv_plafond", "nbr_pneus", "km",
        "status_demande",
    ):
        if f in data:
            setattr(d, f, data[f])
    if "livraison" in data:
        d.livraison = parse_date(data["livraison"])
    db.session.commit()
    return success(d.to_dict())


@bp.post("/demande-voitures/<int:did>/transition")
@jwt_required
def transition_demande(did):
    """Transitions : validation_rh | validation_dgd | validation_achat."""
    d = db.session.get(DemandeVoiture, did)
    if not d:
        return error("Demande introuvable", 404)
    data = request.get_json(silent=True) or {}
    step = data.get("step")
    role = permission_service.role_name(g.current_user)
    now = datetime.utcnow()

    if step == "validation_rh":
        if role not in {"Admin", "RH", "DRH"}:
            return error("Accès refusé", 403)
        d.rh_id = g.current_user.id
        d.date_validation_rh = now
        d.commentaire_rh = data.get("commentaire")
        d.status_demande = data.get("status_demande") or "Validé RH"
    elif step == "validation_dgd":
        if role not in {"Admin", "DGD"}:
            return error("Accès refusé", 403)
        d.dgd_id = g.current_user.id
        d.date_validation_dgd = now
        d.commentaire_dgd = data.get("commentaire")
        d.status_demande = data.get("status_demande") or "Validé DGD"
    elif step == "validation_achat":
        if role not in {"Admin", "Achat", "DRACHAT"}:
            return error("Accès refusé", 403)
        d.achat_id = g.current_user.id
        d.date_validation_achat = now
        d.commentaire_achat = data.get("commentaire")
        d.status_demande = data.get("status_demande") or "Validé Achat"
    else:
        return error("step invalide (validation_rh|validation_dgd|validation_achat)", 400)

    db.session.commit()
    return success(d.to_dict())


@bp.delete("/demande-voitures/<int:did>")
@jwt_required
def delete_demande(did):
    if not permission_service.is_admin(g.current_user):
        return error("Accès refusé", 403)
    d = db.session.get(DemandeVoiture, did)
    if not d:
        return error("Demande introuvable", 404)
    db.session.delete(d)
    db.session.commit()
    return success(message="Supprimé")


# ── Référentiels ──────────────────────────────────────────────

@bp.get("/referentiels")
@jwt_required
def list_referentiels():
    if not permission_service.can(g.current_user, "parc", "read"):
        return error("Accès refusé", 403)
    return success([r.to_dict() for r in Referentiel.query.order_by(Referentiel.id).all()])


@bp.get("/referentiels/<int:rid>")
@jwt_required
def get_referentiel(rid):
    r = db.session.get(Referentiel, rid)
    if not r:
        return error("Référentiel introuvable", 404)
    return success(r.to_dict())


@bp.post("/referentiels")
@jwt_required
def create_referentiel():
    if not permission_service.can(g.current_user, "parc", "write"):
        return error("Accès refusé", 403)
    data = request.get_json(silent=True) or {}
    r = Referentiel(
        modele=data.get("modele") or "",
        duree=data.get("duree") or "",
        kilometrage=data.get("kilometrage") or "",
    )
    for f in (
        "loyer_ht_ald", "loyer_ttc_ald", "km_supp_ht_ald", "nb_pneus_ald",
        "cat_remplacement_ald", "loyer_ht_arval", "loyer_ttc_arval",
        "km_supp_ht_arval", "nb_pneus_arval", "cat_remplacement_arval",
        "moyenne_offres_ttc", "valeur_actuelle_moy_ttc",
        "augmentation_pourcent", "categorie",
    ):
        if f in data:
            setattr(r, f, data[f])
    db.session.add(r)
    db.session.commit()
    return success(r.to_dict(), status=201)


@bp.patch("/referentiels/<int:rid>")
@jwt_required
def update_referentiel(rid):
    if not permission_service.can(g.current_user, "parc", "write"):
        return error("Accès refusé", 403)
    r = db.session.get(Referentiel, rid)
    if not r:
        return error("Référentiel introuvable", 404)
    data = request.get_json(silent=True) or {}
    for f in data:
        if hasattr(r, f) and f != "id":
            setattr(r, f, data[f])
    db.session.commit()
    return success(r.to_dict())


@bp.delete("/referentiels/<int:rid>")
@jwt_required
def delete_referentiel(rid):
    if not permission_service.can(g.current_user, "parc", "write"):
        return error("Accès refusé", 403)
    r = db.session.get(Referentiel, rid)
    if not r:
        return error("Référentiel introuvable", 404)
    db.session.delete(r)
    db.session.commit()
    return success(message="Supprimé")


@bp.post("/referentiels/import")
@jwt_required
def import_referentiels():
    if not permission_service.can(g.current_user, "parc", "write"):
        return error("Accès refusé", 403)
    f = request.files.get("file")
    if not f:
        return error("Fichier Excel requis (file)", 400)
    rows = import_service.parse_referentiels(f)
    for row in rows:
        db.session.add(Referentiel(**row))
    db.session.commit()
    return success({"imported": len(rows)}, status=201)


# ── Requêtes collaborateurs ───────────────────────────────────

@bp.get("/parc/requetes")
@jwt_required
def list_requetes():
    """File des demandes véhicules (vue ParcController requetes_collaborateurs)."""
    if not permission_service.can(g.current_user, "parc", "read"):
        return error("Accès refusé", 403)
    return success([
        _demande_dict(d)
        for d in DemandeVoiture.query.order_by(DemandeVoiture.id.desc()).all()
    ])


@bp.get("/parc/requetes/<int:did>")
@jwt_required
def view_requete(did):
    if not permission_service.can(g.current_user, "parc", "read"):
        return error("Accès refusé", 403)
    d = db.session.get(DemandeVoiture, did)
    if not d:
        return error("Requête introuvable", 404)
    return success(_demande_dict(d))
