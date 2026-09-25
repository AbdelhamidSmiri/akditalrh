"""Fixtures pytest — SQLite en mémoire + jeu de données métier."""
import hashlib
from datetime import date, datetime, timedelta

import pytest

from app import create_app
from app.config import TestingConfig
from app.extensions import db
from app.models.hotel import Chambre, Hotel, HotelPrice
from app.models.role import Role
from app.models.site import Site
from app.models.user import User
from app.models.ville import Ville
from app.services import auth_service


@pytest.fixture
def app():
    application = create_app(TestingConfig)
    with application.app_context():
        db.create_all()

        roles = [
            Role(id=1, role="Admin", plafond_hotel="1000"),
            Role(id=2, role="Agence", plafond_hotel="0"),
            Role(id=4, role="Directeur de projet", plafond_hotel="1000"),
        ]
        db.session.add_all(roles)

        ville = Ville(id=1, ville="CASABLANCA")
        site = Site(
            id=3,
            ville_id=1,
            site="AKDITAL",
            adresse="Casa",
            telephone="0600000000",
            mail="site@test.local",
            created=datetime.utcnow(),
        )
        db.session.add_all([ville, site])

        admin = User(
            id=1,
            role_id=1,
            site_id=3,
            username="admin@test.local",
            password=auth_service.hash_password("Admin123!"),
            etat="Actif",
            nom="Admin",
            prenom="Test",
            totp_enabled=False,
        )
        legacy = User(
            id=2,
            role_id=4,
            site_id=3,
            username="legacy@test.local",
            password=hashlib.sha1(b"Legacy123!").hexdigest(),
            etat="Actif",
            nom="Legacy",
            prenom="User",
            totp_enabled=False,
        )
        agent = User(
            id=3,
            role_id=4,
            site_id=3,
            username="agent@test.local",
            password=auth_service.hash_password("Agent123!"),
            etat="Actif",
            nom="Agent",
            prenom="Collab",
            totp_enabled=False,
        )
        agence = User(
            id=4,
            role_id=2,
            site_id=3,
            username="agence@test.local",
            password=auth_service.hash_password("Agence123!"),
            etat="Actif",
            nom="Agence",
            prenom="Voyage",
            totp_enabled=False,
        )
        db.session.add_all([admin, legacy, agent, agence])

        hotel = Hotel(
            id=1,
            hotel="Hotel Test",
            ville_id=1,
            etoile="4",
            mail="hotel@test.local",
            created=datetime.utcnow(),
        )
        chambre = Chambre(
            id=1,
            hotel_id=1,
            nom="Chambre Single",
            type="CADRE",
            created=datetime.utcnow(),
        )
        price = HotelPrice(
            id=1,
            chambre_id=1,
            date_debut=date.today(),
            date_fin=date.today() + timedelta(days=365),
            prix="800",
            created=datetime.utcnow(),
        )
        db.session.add_all([hotel, chambre, price])
        db.session.commit()

        yield application
        db.session.remove()
        db.drop_all()


@pytest.fixture
def client(app):
    return app.test_client()


def _login(client, username, password):
    resp = client.post(
        "/api/v1/auth/login",
        json={"username": username, "password": password},
    )
    assert resp.status_code == 200, resp.get_json()
    data = resp.get_json()["data"]
    assert "access_token" in data
    return {
        "Authorization": f"Bearer {data['access_token']}",
        "refresh_token": data.get("refresh_token"),
        "user": data.get("user"),
        "raw": data,
    }


@pytest.fixture
def admin_headers(client):
    auth = _login(client, "admin@test.local", "Admin123!")
    return {"Authorization": auth["Authorization"]}


@pytest.fixture
def agent_headers(client):
    auth = _login(client, "agent@test.local", "Agent123!")
    return {"Authorization": auth["Authorization"]}


@pytest.fixture
def agence_headers(client):
    auth = _login(client, "agence@test.local", "Agence123!")
    return {"Authorization": auth["Authorization"]}


@pytest.fixture
def admin_auth(client):
    return _login(client, "admin@test.local", "Admin123!")
