"""Tests vols + espace agence."""


def test_vol_crud_agent(client, agent_headers):
    resp = client.post(
        "/api/v1/vols",
        headers=agent_headers,
        json={
            "site_id": 3,
            "depart": "Casablanca",
            "destination": "Paris",
            "date_aller": "2026-10-20",
            "date_retour": "2026-10-25",
            "num_odm": "VOL-1",
        },
    )
    assert resp.status_code in (200, 201), resp.get_json()
    vid = resp.get_json()["data"]["id"]
    assert resp.get_json()["data"]["etat"] == "En cours"

    resp = client.get("/api/v1/vols", headers=agent_headers)
    assert resp.status_code == 200
    assert any(v["id"] == vid for v in resp.get_json()["data"])

    resp = client.get(f"/api/v1/vols/{vid}", headers=agent_headers)
    assert resp.status_code == 200

    resp = client.patch(
        f"/api/v1/vols/{vid}",
        headers=agent_headers,
        json={"message": "Maj"},
    )
    assert resp.status_code == 200


def test_agence_workflow(client, agent_headers, agence_headers, admin_headers):
    resp = client.post(
        "/api/v1/vols",
        headers=agent_headers,
        json={
            "site_id": 3,
            "depart": "Casa",
            "destination": "Tanger",
            "date_aller": "2026-11-01",
            "date_retour": "2026-11-03",
            "num_odm": "VOL-AG",
        },
    )
    vid = resp.get_json()["data"]["id"]

    resp = client.get("/api/v1/vols/agence", headers=agence_headers)
    assert resp.status_code == 200
    assert any(v["id"] == vid for v in resp.get_json()["data"])

    # Valider
    resp = client.post(
        f"/api/v1/vols/{vid}/valider",
        headers=agence_headers,
        json={"num_vol": "AT123", "prix_vol": "2500", "reponse": "OK"},
    )
    assert resp.status_code == 200, resp.get_json()
    assert resp.get_json()["data"]["etat"] in ("Validé", "Valide", "validé")

    # Nouveau vol pour annuler
    resp = client.post(
        "/api/v1/vols",
        headers=agent_headers,
        json={
            "site_id": 3,
            "depart": "Casa",
            "destination": "Fes",
            "date_aller": "2026-11-10",
            "date_retour": "2026-11-12",
        },
    )
    vid2 = resp.get_json()["data"]["id"]
    resp = client.post(
        f"/api/v1/vols/{vid2}/annuler",
        headers=agence_headers,
        json={"reponse": "Annulé"},
    )
    assert resp.status_code == 200

    # Archive (admin)
    resp = client.post(
        "/api/v1/vols",
        headers=admin_headers,
        json={"site_id": 3, "depart": "Rabat", "destination": "Agadir"},
    )
    vid3 = resp.get_json()["data"]["id"]
    resp = client.post(
        f"/api/v1/vols/{vid3}/archive",
        headers=admin_headers,
        json={},
    )
    assert resp.status_code == 200


def test_agence_forbidden_for_agent(client, agent_headers):
    resp = client.get("/api/v1/vols/agence", headers=agent_headers)
    assert resp.status_code == 403
