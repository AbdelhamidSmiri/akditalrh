"""Tests parc auto, carburant, jawaz, dashboard."""


def test_voitures_crud(client, admin_headers):
    resp = client.post(
        "/api/v1/voitures",
        headers=admin_headers,
        json={
            "user_id": 1,
            "ste_location": "ALD",
            "immatriculation": "12345-A-1",
            "marque": "RENAULT",
            "modele": "Clio",
            "duree_mois": 48,
            "km": 80000,
        },
    )
    assert resp.status_code == 201, resp.get_json()
    vid = resp.get_json()["data"]["id"]

    resp = client.get("/api/v1/voitures", headers=admin_headers)
    assert resp.status_code == 200
    assert any(v["id"] == vid for v in resp.get_json()["data"])

    resp = client.get("/api/v1/voitures/calendrier", headers=admin_headers)
    assert resp.status_code == 200


def test_demande_voitures(client, admin_headers):
    resp = client.post(
        "/api/v1/demande-voitures",
        headers=admin_headers,
        json={
            "marque": "AUDI",
            "modele": "Q3",
            "duree_mois": 48,
            "description": "Besoin véhicule",
        },
    )
    assert resp.status_code == 201, resp.get_json()
    did = resp.get_json()["data"]["id"]

    resp = client.get("/api/v1/demande-voitures", headers=admin_headers)
    assert resp.status_code == 200

    resp = client.post(
        f"/api/v1/demande-voitures/{did}/transition",
        headers=admin_headers,
        json={"step": "validation_rh", "commentaire": "OK RH"},
    )
    assert resp.status_code == 200, resp.get_json()
    assert "Validé RH" in (resp.get_json()["data"]["status_demande"] or "")


def test_referentiels_parc(client, admin_headers):
    resp = client.post(
        "/api/v1/referentiels",
        headers=admin_headers,
        json={
            "modele": "TEST MODEL",
            "duree": "48 MOIS",
            "kilometrage": "100000",
            "categorie": "cadre",
        },
    )
    assert resp.status_code == 201, resp.get_json()
    resp = client.get("/api/v1/referentiels", headers=admin_headers)
    assert resp.status_code == 200
    assert any(r["modele"] == "TEST MODEL" for r in resp.get_json()["data"])


def test_cartes_carburant(client, admin_headers):
    resp = client.post(
        "/api/v1/cartes-carburant",
        headers=admin_headers,
        json={
            "user_id": 1,
            "site_id": 3,
            "prestataire": "AFRIQUIA",
            "identifiant": "708358200000000001",
            "carte_labelle": "TEST CARD",
            "utilisateur": "Admin Test",
            "nv_plafond": 2000,
            "status": "Active",
        },
    )
    assert resp.status_code in (200, 201), resp.get_json()
    cid = resp.get_json()["data"]["id"]
    resp = client.get("/api/v1/cartes-carburant", headers=admin_headers)
    assert resp.status_code == 200
    resp = client.delete(f"/api/v1/cartes-carburant/{cid}", headers=admin_headers)
    assert resp.status_code == 200


def test_tag_jawaz(client, admin_headers):
    resp = client.post(
        "/api/v1/tag-jawaz",
        headers=admin_headers,
        json={"user_id": 1, "site_id": 3, "ref_jawaz": "23350000001"},
    )
    assert resp.status_code in (200, 201), resp.get_json()
    tid = resp.get_json()["data"]["id"]
    resp = client.get("/api/v1/tag-jawaz", headers=admin_headers)
    assert resp.status_code == 200
    resp = client.delete(f"/api/v1/tag-jawaz/{tid}", headers=admin_headers)
    assert resp.status_code == 200


def test_dashboard_stats(client, admin_headers):
    resp = client.get("/api/v1/dashboard/stats", headers=admin_headers)
    assert resp.status_code == 200
    data = resp.get_json()["data"]
    assert "reservations_en_cours" in data
    assert "mes_reservations" in data
    assert "voitures" in data


def test_parc_requetes(client, admin_headers):
    resp = client.get("/api/v1/parc/requetes", headers=admin_headers)
    assert resp.status_code == 200
