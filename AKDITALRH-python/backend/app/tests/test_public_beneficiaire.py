"""Tests endpoints publics bénéficiaires (conditions)."""


def test_public_beneficiaire_conditions(client, admin_headers, app):
    resp = client.post(
        "/api/v1/appartements",
        headers=admin_headers,
        json={"ville_id": 1, "nom": "Apt Pub", "sexe": "Mixte", "capacite": 2},
    )
    aid = resp.get_json()["data"]["id"]

    resp = client.post(
        "/api/v1/beneficiaires",
        headers=admin_headers,
        json={
            "site_id": 3,
            "appartement_id": aid,
            "nom": "Pub Benef",
            "sexe": "Homme",
        },
    )
    assert resp.status_code == 201, resp.get_json()
    token = resp.get_json()["data"]["conditions_token"]

    resp = client.get(f"/api/v1/public/beneficiaires/{token}/conditions")
    assert resp.status_code == 200

    resp = client.post(
        f"/api/v1/public/beneficiaires/{token}/conditions",
        json={"accept": True},
    )
    assert resp.status_code == 200, resp.get_json()
    assert resp.get_json()["data"]["etat"] == "Valider"


def test_public_beneficiaire_bad_token(client):
    resp = client.get("/api/v1/public/beneficiaires/invalid/conditions")
    assert resp.status_code == 400
