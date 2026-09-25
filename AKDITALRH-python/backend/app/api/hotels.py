"""CRUD Hotels + chambres + prices + available (plafond)."""
from datetime import datetime

from flask import Blueprint, g, request

from app.extensions import db
from app.models.hotel import Chambre, Hotel, HotelPrice
from app.models.ville import Ville
from app.schemas.serializers import parse_date
from app.services import permission_service
from app.utils.decorators import jwt_required
from app.utils.responses import error, success

bp = Blueprint("hotels", __name__)

HOTEL_FIELDS = [
    "hotel", "nom", "etoile", "region", "ville_id", "adresse",
    "images", "mail", "telephone", "nom_responsable", "reglement",
]


def _hotel_dict(hotel: Hotel, villes: dict[int, Ville] | None = None, **kwargs) -> dict:
    data = hotel.to_dict(**kwargs)
    if villes is None:
        ville = db.session.get(Ville, hotel.ville_id)
    else:
        ville = villes.get(hotel.ville_id)
    data["ville"] = ville.to_dict() if ville else None
    return data


def _resolve_ville_autre(data: dict) -> None:
    """« Autre ville » : réutilise la ville existante (insensible à la casse) ou la crée en MAJUSCULES."""
    name = (data.pop("ville_autre", None) or "").strip().upper()
    if not name:
        return
    ville = Ville.query.filter(db.func.upper(Ville.ville) == name).first()
    if not ville:
        ville = Ville(ville=name)
        db.session.add(ville)
        db.session.flush()
    data["ville_id"] = ville.id


@bp.get("/available")
@jwt_required
def hotels_available():
    """Filtre ville + dates + plafond_hotel du rôle."""
    if not permission_service.can(g.current_user, "hotels", "read"):
        return error("Accès refusé", 403)

    ville_id = request.args.get("ville_id", type=int)
    checkin = parse_date(request.args.get("checkin"))
    checkout = parse_date(request.args.get("checkout"))
    plafond = g.current_user.role.plafond_numeric() if g.current_user.role else 0

    q = Hotel.query
    if ville_id:
        q = q.filter_by(ville_id=ville_id)

    result = []
    for hotel in q.all():
        chambres_ok = []
        for chambre in hotel.chambres:
            prices = list(chambre.prices)
            matching = []
            for p in prices:
                if checkin and checkout and p.date_debut and p.date_fin:
                    # Chevauchement période
                    if p.date_fin < checkin or p.date_debut > checkout:
                        continue
                prix = p.prix_numeric()
                if plafond > 0 and prix > plafond:
                    continue
                matching.append(p.to_dict())
            if matching or (not checkin and not checkout and prices):
                # Sans dates : filtrer sur n'importe quel prix sous plafond
                if not checkin and not checkout:
                    matching = [
                        p.to_dict()
                        for p in prices
                        if plafond <= 0 or p.prix_numeric() <= plafond
                    ]
                if matching:
                    c = chambre.to_dict()
                    c["prices"] = matching
                    chambres_ok.append(c)
        if chambres_ok:
            h = hotel.to_dict()
            h["chambres"] = chambres_ok
            result.append(h)
    return success({"plafond_hotel": plafond, "hotels": result})


@bp.get("")
@jwt_required
def list_hotels():
    if not permission_service.can(g.current_user, "hotels", "read"):
        return error("Accès refusé", 403)
    ville_id = request.args.get("ville_id", type=int)
    q = Hotel.query
    if ville_id:
        q = q.filter_by(ville_id=ville_id)
    villes = {v.id: v for v in Ville.query.all()}
    return success([_hotel_dict(h, villes) for h in q.order_by(Hotel.id).all()])


@bp.get("/<int:hotel_id>")
@jwt_required
def get_hotel(hotel_id):
    if not permission_service.can(g.current_user, "hotels", "read"):
        return error("Accès refusé", 403)
    hotel = db.session.get(Hotel, hotel_id)
    if not hotel:
        return error("Hôtel introuvable", 404)
    return success(_hotel_dict(hotel, with_chambres=True))


@bp.post("")
@jwt_required
def create_hotel():
    if not permission_service.can(g.current_user, "hotels", "write"):
        return error("Accès refusé", 403)
    data = request.get_json(silent=True) or {}
    _resolve_ville_autre(data)
    hotel = Hotel(created=datetime.utcnow())
    for f in HOTEL_FIELDS:
        if f in data:
            setattr(hotel, f, data[f])
    if not hotel.ville_id:
        return error("ville_id requis", 400)
    db.session.add(hotel)
    db.session.commit()
    return success(_hotel_dict(hotel), status=201)


@bp.patch("/<int:hotel_id>")
@jwt_required
def update_hotel(hotel_id):
    if not permission_service.can(g.current_user, "hotels", "write"):
        return error("Accès refusé", 403)
    hotel = db.session.get(Hotel, hotel_id)
    if not hotel:
        return error("Hôtel introuvable", 404)
    data = request.get_json(silent=True) or {}
    _resolve_ville_autre(data)
    for f in HOTEL_FIELDS:
        if f in data:
            setattr(hotel, f, data[f])
    db.session.commit()
    return success(_hotel_dict(hotel, with_chambres=True))


@bp.delete("/<int:hotel_id>")
@jwt_required
def delete_hotel(hotel_id):
    if not permission_service.can(g.current_user, "hotels", "write"):
        return error("Accès refusé", 403)
    hotel = db.session.get(Hotel, hotel_id)
    if not hotel:
        return error("Hôtel introuvable", 404)
    db.session.delete(hotel)
    db.session.commit()
    return success(message="Supprimé")


@bp.get("/<int:hotel_id>/chambres")
@jwt_required
def list_chambres(hotel_id):
    if not permission_service.can(g.current_user, "hotels", "read"):
        return error("Accès refusé", 403)
    hotel = db.session.get(Hotel, hotel_id)
    if not hotel:
        return error("Hôtel introuvable", 404)
    return success([c.to_dict(with_prices=True) for c in hotel.chambres])


@bp.post("/<int:hotel_id>/chambres")
@jwt_required
def create_chambre(hotel_id):
    if not permission_service.can(g.current_user, "hotels", "write"):
        return error("Accès refusé", 403)
    hotel = db.session.get(Hotel, hotel_id)
    if not hotel:
        return error("Hôtel introuvable", 404)
    data = request.get_json(silent=True) or {}
    chambre = Chambre(
        hotel_id=hotel_id,
        nom=data.get("nom"),
        type=data.get("type"),
        images=data.get("images"),
        created=datetime.utcnow(),
    )
    db.session.add(chambre)
    db.session.flush()
    for p in data.get("prices") or []:
        price = HotelPrice(
            chambre_id=chambre.id,
            date_debut=parse_date(p.get("date_debut")),
            date_fin=parse_date(p.get("date_fin")),
            prix=str(p.get("prix")) if p.get("prix") is not None else None,
            created=datetime.utcnow(),
        )
        db.session.add(price)
    db.session.commit()
    return success(chambre.to_dict(with_prices=True), status=201)


@bp.patch("/chambres/<int:chambre_id>")
@jwt_required
def update_chambre(chambre_id):
    if not permission_service.can(g.current_user, "hotels", "write"):
        return error("Accès refusé", 403)
    chambre = db.session.get(Chambre, chambre_id)
    if not chambre:
        return error("Chambre introuvable", 404)
    data = request.get_json(silent=True) or {}
    for f in ("nom", "type", "images"):
        if f in data:
            setattr(chambre, f, data[f])
    db.session.commit()
    return success(chambre.to_dict(with_prices=True))


@bp.delete("/chambres/<int:chambre_id>")
@jwt_required
def delete_chambre(chambre_id):
    if not permission_service.can(g.current_user, "hotels", "write"):
        return error("Accès refusé", 403)
    chambre = db.session.get(Chambre, chambre_id)
    if not chambre:
        return error("Chambre introuvable", 404)
    # Cake : hasMany Hotelprice 'dependent' => true
    HotelPrice.query.filter_by(chambre_id=chambre.id).delete(synchronize_session=False)
    db.session.delete(chambre)
    db.session.commit()
    return success(message="Supprimé")


@bp.get("/chambres/<int:chambre_id>/prices")
@jwt_required
def list_prices(chambre_id):
    if not permission_service.can(g.current_user, "hotels", "read"):
        return error("Accès refusé", 403)
    prices = HotelPrice.query.filter_by(chambre_id=chambre_id).all()
    return success([p.to_dict() for p in prices])


@bp.post("/chambres/<int:chambre_id>/prices")
@jwt_required
def create_price(chambre_id):
    if not permission_service.can(g.current_user, "hotels", "write"):
        return error("Accès refusé", 403)
    chambre = db.session.get(Chambre, chambre_id)
    if not chambre:
        return error("Chambre introuvable", 404)
    data = request.get_json(silent=True) or {}
    price = HotelPrice(
        chambre_id=chambre_id,
        date_debut=parse_date(data.get("date_debut")),
        date_fin=parse_date(data.get("date_fin")),
        prix=str(data.get("prix")) if data.get("prix") is not None else None,
        created=datetime.utcnow(),
    )
    db.session.add(price)
    db.session.commit()
    return success(price.to_dict(), status=201)


@bp.put("/chambres/<int:chambre_id>/prices")
@jwt_required
def sync_prices(chambre_id):
    """« Mettre à jour les prix » (Hotelprices::edit) : suppressions + créations / mises à jour."""
    if not permission_service.can(g.current_user, "hotels", "write"):
        return error("Accès refusé", 403)
    chambre = db.session.get(Chambre, chambre_id)
    if not chambre:
        return error("Chambre introuvable", 404)
    data = request.get_json(silent=True) or {}

    delete_ids = [int(i) for i in data.get("delete_ids") or [] if str(i).isdigit()]
    deleted = 0
    if delete_ids:
        deleted = HotelPrice.query.filter(
            HotelPrice.chambre_id == chambre_id, HotelPrice.id.in_(delete_ids)
        ).delete(synchronize_session=False)

    saved = 0
    for p in data.get("prices") or []:
        debut, fin, prix = parse_date(p.get("date_debut")), parse_date(p.get("date_fin")), p.get("prix")
        if not debut or not fin or prix in (None, ""):
            continue
        price = None
        if p.get("id"):
            price = HotelPrice.query.filter_by(id=p["id"], chambre_id=chambre_id).first()
        if not price:
            price = HotelPrice(chambre_id=chambre_id, created=datetime.utcnow())
            db.session.add(price)
        price.date_debut, price.date_fin, price.prix = debut, fin, str(prix)
        saved += 1
    db.session.commit()

    if saved and deleted:
        message = "Les prix ont été sauvegardés et supprimés avec succès."
    elif saved:
        message = "Les prix de la chambre ont été sauvegardés avec succès."
    elif deleted:
        message = "Les prix de la chambre ont été supprimés avec succès."
    else:
        return error("Aucun prix n'a été sauvegardé.", 400)
    return success(chambre.to_dict(with_prices=True), message=message)


@bp.patch("/prices/<int:price_id>")
@jwt_required
def update_price(price_id):
    if not permission_service.can(g.current_user, "hotels", "write"):
        return error("Accès refusé", 403)
    price = db.session.get(HotelPrice, price_id)
    if not price:
        return error("Tarif introuvable", 404)
    data = request.get_json(silent=True) or {}
    if "date_debut" in data:
        price.date_debut = parse_date(data["date_debut"])
    if "date_fin" in data:
        price.date_fin = parse_date(data["date_fin"])
    if "prix" in data:
        price.prix = str(data["prix"])
    db.session.commit()
    return success(price.to_dict())


@bp.delete("/prices/<int:price_id>")
@jwt_required
def delete_price(price_id):
    if not permission_service.can(g.current_user, "hotels", "write"):
        return error("Accès refusé", 403)
    price = db.session.get(HotelPrice, price_id)
    if not price:
        return error("Tarif introuvable", 404)
    db.session.delete(price)
    db.session.commit()
    return success(message="Supprimé")
