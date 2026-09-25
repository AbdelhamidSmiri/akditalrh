"""Tests réservations + liens publics accept/reject."""
from app.services import token_service


def test_reservation_crud(client, admin_headers, app):
    resp = client.post(
        "/api/v1/reservations",
        headers=admin_headers,
        json={
            "chambre_id": 1,
            "site_id": 3,
            "checkin": "2026-10-01",
            "checkout": "2026-10-03",
            "num_odm": "ODM-TEST-1",
            "message": "Mission test",
        },
    )
    assert resp.status_code in (200, 201), resp.get_json()
    resa = resp.get_json()["data"]
    rid = resa["id"]
    assert resa["etat"] == "En cours"

    resp = client.get("/api/v1/reservations", headers=admin_headers)
    assert resp.status_code == 200
    assert any(r["id"] == rid for r in resp.get_json()["data"])

    resp = client.get(f"/api/v1/reservations/{rid}", headers=admin_headers)
    assert resp.status_code == 200

    resp = client.patch(
        f"/api/v1/reservations/{rid}",
        headers=admin_headers,
        json={"message": "Mis à jour"},
    )
    assert resp.status_code == 200


def test_public_accept_reject(client, admin_headers, app):
    resp = client.post(
        "/api/v1/reservations",
        headers=admin_headers,
        json={
            "chambre_id": 1,
            "site_id": 3,
            "checkin": "2026-11-01",
            "checkout": "2026-11-02",
            "num_odm": "ODM-PUB",
        },
    )
    rid = resp.get_json()["data"]["id"]

    with app.app_context():
        token = token_service.make_token(
            {"rid": rid, "action": "accept"},
            salt=token_service.SALT_RESERVATION,
        )

    resp = client.get(f"/api/v1/public/reservations/{token}/accept")
    assert resp.status_code == 200

    resp = client.post(
        f"/api/v1/public/reservations/{token}/accept",
        json={"reponse": "OK chambre dispo"},
    )
    assert resp.status_code == 200
    assert resp.get_json()["data"]["etat"] in ("Accepté", "acceptée", "Validé")

    # Nouveau token reject
    resp = client.post(
        "/api/v1/reservations",
        headers=admin_headers,
        json={
            "chambre_id": 1,
            "site_id": 3,
            "checkin": "2026-12-01",
            "checkout": "2026-12-02",
            "num_odm": "ODM-REJ",
        },
    )
    rid2 = resp.get_json()["data"]["id"]
    with app.app_context():
        token2 = token_service.make_token(
            {"rid": rid2},
            salt=token_service.SALT_RESERVATION,
        )
    resp = client.post(
        f"/api/v1/public/reservations/{token2}/reject",
        json={"reponse": "Complet"},
    )
    assert resp.status_code == 200


def test_public_invalid_token(client):
    resp = client.post("/api/v1/public/reservations/bad-token/accept", json={})
    assert resp.status_code == 400


def test_agent_sees_own_reservations(client, agent_headers, admin_headers):
    # Admin crée pour lui
    client.post(
        "/api/v1/reservations",
        headers=admin_headers,
        json={"chambre_id": 1, "site_id": 3, "checkin": "2026-10-10", "checkout": "2026-10-11"},
    )
    # Agent crée la sienne
    resp = client.post(
        "/api/v1/reservations",
        headers=agent_headers,
        json={"chambre_id": 1, "site_id": 3, "checkin": "2026-10-12", "checkout": "2026-10-13"},
    )
    assert resp.status_code in (200, 201), resp.get_json()
    my_id = resp.get_json()["data"]["id"]

    resp = client.get("/api/v1/reservations", headers=agent_headers)
    assert resp.status_code == 200
    ids = [r["id"] for r in resp.get_json()["data"]]
    assert my_id in ids
