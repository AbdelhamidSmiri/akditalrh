"""CRUD Appartements + Bénéficiaires + disponibilités + recherche."""
from datetime import datetime

from flask import Blueprint, g, request
from sqlalchemy import or_

from app.extensions import db
from app.models.logement import Appartement, Beneficiaire
from app.models.site import Site
from app.models.user import User
from app.models.ville import Ville
from app.schemas.serializers import parse_date
from app.services import permission_service, token_service
from app.utils.decorators import jwt_required
from app.utils.responses import error, success

bp = Blueprint("logement", __name__)


def _ville_name(ville_id):
    if not ville_id:
        return "Non définie"
    v = db.session.get(Ville, ville_id)
    return v.ville if v else "Non définie"


def _count_ocupants(appartement_id, etat="Checkin"):
    return Beneficiaire.query.filter_by(
        appartement_id=appartement_id, etat=etat
    ).count()


def _appartement_dict(apt, with_occupancy=False):
    data = apt.to_dict()
    data["ville"] = {"id": apt.ville_id, "ville": _ville_name(apt.ville_id)} if apt.ville_id else None
    if with_occupancy:
        cap = int(apt.capacite or 0)
        ocupants = _count_ocupants(apt.id)
        data["ocupants"] = ocupants
        data["places_dispo"] = max(cap - ocupants, 0)
    return data


def _beneficiaire_dict(ben):
    data = ben.to_dict()
    if ben.site_id:
        site = db.session.get(Site, ben.site_id)
        data["site"] = {"id": site.id, "site": site.site} if site else None
    else:
        data["site"] = None
    if ben.user_id:
        user = db.session.get(User, ben.user_id)
        data["user"] = {
            "id": user.id,
            "nom": user.nom,
            "prenom": user.prenom,
            "username": user.username,
        } if user else None
    else:
        data["user"] = None
    if ben.appartement_id:
        apt = db.session.get(Appartement, ben.appartement_id)
        data["appartement"] = (
            {"id": apt.id, "nom": apt.nom, "sexe": apt.sexe, "capacite": apt.capacite}
            if apt
            else None
        )
    else:
        data["appartement"] = None
    return data


# ── Appartements ──────────────────────────────────────────────

@bp.get("/appartements")
@jwt_required
def list_appartements():
    if not permission_service.can(g.current_user, "logement", "read"):
        return error("Accès refusé", 403)
    return success([
        _appartement_dict(a, with_occupancy=True)
        for a in Appartement.query.order_by(Appartement.id.desc()).all()
    ])


@bp.get("/appartements/disponibilites")
@jwt_required
def disponibilites():
    """Tableau de bord Cake Appartements/index."""
    if not permission_service.can(g.current_user, "logement", "read"):
        return error("Accès refusé", 403)

    appartements = Appartement.query.order_by(Appartement.id).all()
    stats = {}
    appart_info = []
    global_stats = {
        "total_appartements": 0,
        "total_ocupes": 0,
        "total_places_dispo": 0,
    }

    for a in appartements:
        ville = _ville_name(a.ville_id)
        sexe = a.sexe or ""
        cap = int(a.capacite or 0)
        nb_ocupants = _count_ocupants(a.id)
        dispo = cap - nb_ocupants

        if ville not in stats:
            stats[ville] = {}
        if sexe not in stats[ville]:
            stats[ville][sexe] = {"total": 0, "ocupes": 0, "places_dispo": 0}

        stats[ville][sexe]["total"] += 1
        if nb_ocupants >= cap and cap > 0:
            stats[ville][sexe]["ocupes"] += 1
        stats[ville][sexe]["places_dispo"] += max(dispo, 0)

        appart_info.append({
            "id": a.id,
            "nom": a.nom,
            "ville": ville,
            "sexe": sexe,
            "capacite": cap,
            "ocupants": nb_ocupants,
            "places_dispo": max(dispo, 0),
        })

        global_stats["total_appartements"] += 1
        if nb_ocupants >= cap and cap > 0:
            global_stats["total_ocupes"] += 1
        global_stats["total_places_dispo"] += max(dispo, 0)

    # Flatten stats for frontend table
    stats_rows = []
    for ville, by_sexe in stats.items():
        for sexe, info in by_sexe.items():
            stats_rows.append({
                "ville": ville,
                "sexe": sexe,
                "total": info["total"],
                "ocupes": info["ocupes"],
                "places_dispo": info["places_dispo"],
            })

    return success({
        "global": global_stats,
        "stats": stats_rows,
        "appart_info": appart_info,
    })


@bp.get("/appartements/<int:apt_id>")
@jwt_required
def get_appartement(apt_id):
    if not permission_service.can(g.current_user, "logement", "read"):
        return error("Accès refusé", 403)
    apt = db.session.get(Appartement, apt_id)
    if not apt:
        return error("Appartement introuvable", 404)
    return success(_appartement_dict(apt, with_occupancy=True))


@bp.post("/appartements")
@jwt_required
def create_appartement():
    if not permission_service.can(g.current_user, "logement", "write"):
        return error("Accès refusé", 403)
    data = request.get_json(silent=True) or {}
    apt = Appartement(
        ville_id=data.get("ville_id"),
        nom=data.get("nom"),
        sexe=data.get("sexe"),
        capacite=data.get("capacite"),
        adresse=data.get("adresse"),
        images=data.get("images"),
        created=datetime.utcnow(),
    )
    db.session.add(apt)
    db.session.commit()
    return success(_appartement_dict(apt, with_occupancy=True), status=201)


@bp.patch("/appartements/<int:apt_id>")
@jwt_required
def update_appartement(apt_id):
    if not permission_service.can(g.current_user, "logement", "write"):
        return error("Accès refusé", 403)
    apt = db.session.get(Appartement, apt_id)
    if not apt:
        return error("Appartement introuvable", 404)
    data = request.get_json(silent=True) or {}
    for f in ("ville_id", "nom", "sexe", "capacite", "adresse", "images"):
        if f in data:
            setattr(apt, f, data[f])
    db.session.commit()
    return success(_appartement_dict(apt, with_occupancy=True))


@bp.delete("/appartements/<int:apt_id>")
@jwt_required
def delete_appartement(apt_id):
    if not permission_service.can(g.current_user, "logement", "write"):
        return error("Accès refusé", 403)
    apt = db.session.get(Appartement, apt_id)
    if not apt:
        return error("Appartement introuvable", 404)
    db.session.delete(apt)
    db.session.commit()
    return success(message="Supprimé")


@bp.post("/appartements/recherche")
@jwt_required
def recherche_appartements():
    """Appartements disponibles pour une période (Cake BeneficiairesController::recherche)."""
    if not permission_service.can(g.current_user, "logement", "read"):
        return error("Accès refusé", 403)
    data = request.get_json(silent=True) or {}
    date_debut = parse_date(data.get("date_debut"))
    date_fin = parse_date(data.get("date_fin"))
    sexe = data.get("sexe")
    ville_id = data.get("ville_id")
    if not date_debut or not date_fin or not sexe or not ville_id:
        return error("date_debut, date_fin, sexe et ville_id requis", 400)

    q = Appartement.query.filter_by(sexe=sexe, ville_id=int(ville_id))
    disponibles = []
    for apt in q.all():
        occupants = Beneficiaire.query.filter(
            Beneficiaire.appartement_id == apt.id,
            Beneficiaire.etat != "Annuler",
            Beneficiaire.date_debut <= date_fin,
            Beneficiaire.date_fin >= date_debut,
        ).count()
        cap = int(apt.capacite or 0)
        if occupants < cap:
            item = _appartement_dict(apt)
            item["nb_occupants"] = occupants
            item["places_dispo"] = cap - occupants
            disponibles.append(item)
    return success(disponibles)


# ── Bénéficiaires ─────────────────────────────────────────────

@bp.get("/beneficiaires")
@jwt_required
def list_beneficiaires():
    if not permission_service.can(g.current_user, "logement", "read"):
        return error("Accès refusé", 403)
    q = Beneficiaire.query
    if request.args.get("etat"):
        q = q.filter_by(etat=request.args["etat"])
    if request.args.get("status"):
        q = q.filter_by(status=request.args["status"])
    if request.args.get("site_id"):
        q = q.filter_by(site_id=int(request.args["site_id"]))
    if request.args.get("appartement_id"):
        q = q.filter_by(appartement_id=int(request.args["appartement_id"]))
    if request.args.get("date_debut"):
        d = parse_date(request.args["date_debut"])
        if d:
            q = q.filter(Beneficiaire.date_debut >= d)
    if request.args.get("date_fin"):
        d = parse_date(request.args["date_fin"])
        if d:
            q = q.filter(Beneficiaire.date_fin <= d)
    search = request.args.get("q")
    if search:
        like = f"%{search}%"
        q = q.filter(
            or_(
                Beneficiaire.nom.ilike(like),
                Beneficiaire.mail.ilike(like),
                Beneficiaire.telephone.ilike(like),
            )
        )
    return success([
        _beneficiaire_dict(b) for b in q.order_by(Beneficiaire.id.desc()).all()
    ])


@bp.get("/beneficiaires/<int:ben_id>")
@jwt_required
def get_beneficiaire(ben_id):
    if not permission_service.can(g.current_user, "logement", "read"):
        return error("Accès refusé", 403)
    ben = db.session.get(Beneficiaire, ben_id)
    if not ben:
        return error("Bénéficiaire introuvable", 404)
    return success(_beneficiaire_dict(ben))


@bp.post("/beneficiaires")
@jwt_required
def create_beneficiaire():
    if not permission_service.can(g.current_user, "logement", "write"):
        return error("Accès refusé", 403)
    data = request.get_json(silent=True) or {}
    # Compat aliases frontend
    date_debut = data.get("date_debut") or data.get("date_entree")
    date_fin = data.get("date_fin") or data.get("date_sortie")
    ben = Beneficiaire(
        site_id=data.get("site_id"),
        user_id=data.get("user_id") or g.current_user.id,
        appartement_id=data.get("appartement_id"),
        nom=data.get("nom"),
        sexe=data.get("sexe"),
        mail=data.get("mail"),
        telephone=data.get("telephone"),
        status=data.get("status"),
        etat=data.get("etat") or "En cours",
        date_debut=parse_date(date_debut),
        date_fin=parse_date(date_fin),
        note=data.get("note"),
        created=datetime.utcnow(),
    )
    db.session.add(ben)
    db.session.commit()
    token = token_service.make_token(
        {"bid": ben.id},
        salt=token_service.SALT_BENEFICIAIRE,
    )
    return success({"beneficiaire": _beneficiaire_dict(ben), "conditions_token": token}, status=201)


@bp.patch("/beneficiaires/<int:ben_id>")
@jwt_required
def update_beneficiaire(ben_id):
    if not permission_service.can(g.current_user, "logement", "write"):
        return error("Accès refusé", 403)
    ben = db.session.get(Beneficiaire, ben_id)
    if not ben:
        return error("Bénéficiaire introuvable", 404)
    data = request.get_json(silent=True) or {}
    for f in (
        "site_id", "user_id", "appartement_id", "nom", "sexe", "mail",
        "telephone", "status", "note", "etat",
    ):
        if f in data:
            setattr(ben, f, data[f])
    if "date_debut" in data or "date_entree" in data:
        ben.date_debut = parse_date(data.get("date_debut") or data.get("date_entree"))
    if "date_fin" in data or "date_sortie" in data:
        ben.date_fin = parse_date(data.get("date_fin") or data.get("date_sortie"))
    db.session.commit()
    return success(_beneficiaire_dict(ben))


@bp.delete("/beneficiaires/<int:ben_id>")
@jwt_required
def delete_beneficiaire(ben_id):
    if not permission_service.can(g.current_user, "logement", "write"):
        return error("Accès refusé", 403)
    ben = db.session.get(Beneficiaire, ben_id)
    if not ben:
        return error("Bénéficiaire introuvable", 404)
    db.session.delete(ben)
    db.session.commit()
    return success(message="Supprimé")


@bp.post("/beneficiaires/<int:ben_id>/transition")
@jwt_required
def transition_beneficiaire(ben_id):
    if not permission_service.can(g.current_user, "logement", "write"):
        return error("Accès refusé", 403)
    ben = db.session.get(Beneficiaire, ben_id)
    if not ben:
        return error("Bénéficiaire introuvable", 404)
    data = request.get_json(silent=True) or {}
    new_etat = data.get("etat")
    if not new_etat:
        return error("etat requis", 400)
    current = ben.etat or "En cours"
    allowed = Beneficiaire.TRANSITIONS.get(current, [])
    if new_etat not in allowed and not permission_service.is_admin(g.current_user):
        return error(
            f"Transition {current} → {new_etat} interdite. Autorisées: {allowed}",
            400,
            code="invalid_transition",
        )
    ben.etat = new_etat
    db.session.commit()
    return success(_beneficiaire_dict(ben))
