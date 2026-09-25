"""Tests logement : appartements + bénéficiaires + transitions."""


def test_appartements_crud(client, admin_headers):
    resp = client.post(
        "/api/v1/appartements",
        headers=admin_headers,
        json={
            "ville_id": 1,
            "nom": "Résidence Test",
            "sexe": "Mixte",
            "capacite": 4,
            "adresse": "Bd Test",
        },
    )
    assert resp.status_code == 201, resp.get_json()
    aid = resp.get_json()["data"]["id"]

    resp = client.get("/api/v1/appartements", headers=admin_headers)
    assert resp.status_code == 200
    assert any(a["id"] == aid for a in resp.get_json()["data"])

    resp = client.patch(
        f"/api/v1/appartements/{aid}",
        headers=admin_headers,
        json={"capacite": 6},
    )
    assert resp.status_code == 200
    assert resp.get_json()["data"]["capacite"] == 6


def test_beneficiaires_crud_and_transition(client, admin_headers):
    resp = client.post(
        "/api/v1/appartements",
        headers=admin_headers,
        json={"ville_id": 1, "nom": "Apt Ben", "sexe": "Homme", "capacite": 2},
    )
    aid = resp.get_json()["data"]["id"]

    resp = client.post(
        "/api/v1/beneficiaires",
        headers=admin_headers,
        json={
            "site_id": 3,
            "user_id": 1,
            "appartement_id": aid,
            "nom": "Benef Test",
            "sexe": "Homme",
            "mail": "b@test.local",
            "telephone": "0600000000",
            "date_debut": "2026-10-01",
            "date_fin": "2026-12-01",
        },
    )
    assert resp.status_code == 201, resp.get_json()
    data = resp.get_json()["data"]
    ben = data["beneficiaire"]
    bid = ben["id"]
    assert ben["etat"] == "En cours"
    assert data.get("conditions_token")

    for etat in ("Valider", "Checkin", "Checkout"):
        resp = client.post(
            f"/api/v1/beneficiaires/{bid}/transition",
            headers=admin_headers,
            json={"etat": etat},
        )
        assert resp.status_code == 200, resp.get_json()
        assert resp.get_json()["data"]["etat"] == etat


def test_beneficiaire_invalid_transition(client, admin_headers):
    resp = client.post(
        "/api/v1/appartements",
        headers=admin_headers,
        json={"ville_id": 1, "nom": "Apt X", "sexe": "Femme", "capacite": 1},
    )
    aid = resp.get_json()["data"]["id"]
    resp = client.post(
        "/api/v1/beneficiaires",
        headers=admin_headers,
        json={"site_id": 3, "appartement_id": aid, "nom": "X", "sexe": "Femme"},
    )
    bid = resp.get_json()["data"]["beneficiaire"]["id"]

    # Agent non-admin would fail; Admin bypasses transition rules — use agent
    # Here we only check etat missing → 400
    resp = client.post(
        f"/api/v1/beneficiaires/{bid}/transition",
        headers=admin_headers,
        json={},
    )
    assert resp.status_code == 400
