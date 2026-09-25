"""Serveur E2E isolé (SQLite fichier + données seed).

Usage:
  python e2e_server.py
"""
from __future__ import annotations

import hashlib
import os
import sys
from datetime import date, datetime, timedelta
from pathlib import Path

# Backend root
ROOT = Path(__file__).resolve().parent
sys.path.insert(0, str(ROOT))
os.chdir(ROOT)

from app import create_app  # noqa: E402
from app.config import TestingConfig  # noqa: E402
from app.extensions import db  # noqa: E402
from app.models.hotel import Chambre, Hotel, HotelPrice  # noqa: E402
from app.models.role import Role  # noqa: E402
from app.models.site import Site  # noqa: E402
from app.models.user import User  # noqa: E402
from app.models.ville import Ville  # noqa: E402
from app.services import auth_service  # noqa: E402


class E2EConfig(TestingConfig):
    """SQLite fichier partagé pour le process Flask E2E."""

    SQLALCHEMY_DATABASE_URI = f"sqlite:///{ROOT / 'e2e.db'}"
    MFA_REQUIRED_ROLES = []
    CORS_ORIGINS = [
        "http://localhost:5173",
        "http://127.0.0.1:5173",
        "http://localhost:5174",
        "http://127.0.0.1:5174",
    ]


def seed():
    if Role.query.first():
        return

    db.session.add_all(
        [
            Role(id=1, role="Admin", plafond_hotel="1000"),
            Role(id=2, role="Agence", plafond_hotel="0"),
            Role(id=4, role="Directeur de projet", plafond_hotel="1000"),
        ]
    )
    db.session.add(Ville(id=1, ville="CASABLANCA"))
    db.session.add(
        Site(
            id=3,
            ville_id=1,
            site="AKDITAL",
            adresse="Casa",
            telephone="0600000000",
            mail="site@e2e.local",
            created=datetime.utcnow(),
        )
    )
    db.session.add_all(
        [
            User(
                id=1,
                role_id=1,
                site_id=3,
                username="admin@e2e.local",
                password=auth_service.hash_password("Admin123!"),
                etat="Actif",
                nom="Admin",
                prenom="E2E",
                totp_enabled=False,
            ),
            User(
                id=3,
                role_id=4,
                site_id=3,
                username="agent@e2e.local",
                password=auth_service.hash_password("Agent123!"),
                etat="Actif",
                nom="Agent",
                prenom="E2E",
                totp_enabled=False,
            ),
            User(
                id=4,
                role_id=2,
                site_id=3,
                username="agence@e2e.local",
                password=auth_service.hash_password("Agence123!"),
                etat="Actif",
                nom="Agence",
                prenom="E2E",
                totp_enabled=False,
            ),
            User(
                id=2,
                role_id=4,
                site_id=3,
                username="legacy@e2e.local",
                password=hashlib.sha1(b"Legacy123!").hexdigest(),
                etat="Actif",
                nom="Legacy",
                prenom="E2E",
                totp_enabled=False,
            ),
        ]
    )
    hotel = Hotel(
        id=1,
        hotel="Hotel E2E",
        ville_id=1,
        etoile="4",
        mail="hotel@e2e.local",
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


def main():
    db_path = ROOT / "e2e.db"
    if db_path.exists():
        db_path.unlink()

    app = create_app(E2EConfig)
    with app.app_context():
        db.create_all()
        seed()

    port = int(os.getenv("E2E_API_PORT", "5002"))
    print(f"[e2e] API ready on http://127.0.0.1:{port}/api/v1/health", flush=True)
    app.run(host="127.0.0.1", port=port, debug=False, use_reloader=False)


if __name__ == "__main__":
    main()
