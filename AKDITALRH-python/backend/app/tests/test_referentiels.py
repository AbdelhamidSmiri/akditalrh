"""Tests CRUD référentiels : rôles, villes, sites, users."""


def test_roles_crud(client, admin_headers):
    # list
    resp = client.get("/api/v1/roles", headers=admin_headers)
    assert resp.status_code == 200
    assert len(resp.get_json()["data"]) >= 2

    # create
    resp = client.post(
        "/api/v1/roles",
        headers=admin_headers,
        json={"role": "TestRole", "plafond_hotel": "500"},
    )
    assert resp.status_code in (200, 201)
    rid = resp.get_json()["data"]["id"]

    # update
    resp = client.patch(
        f"/api/v1/roles/{rid}",
        headers=admin_headers,
        json={"plafond_hotel": "750"},
    )
    assert resp.status_code == 200
    assert resp.get_json()["data"]["plafond_hotel"] == "750"

    # delete
    resp = client.delete(f"/api/v1/roles/{rid}", headers=admin_headers)
    assert resp.status_code == 200


def test_villes_sites_crud(client, admin_headers):
    resp = client.get("/api/v1/villes", headers=admin_headers)
    assert resp.status_code == 200
    assert any(v["ville"] == "CASABLANCA" for v in resp.get_json()["data"])

    resp = client.post(
        "/api/v1/villes",
        headers=admin_headers,
        json={"ville": "RABAT"},
    )
    assert resp.status_code in (200, 201)
    vid = resp.get_json()["data"]["id"]

    resp = client.get("/api/v1/sites", headers=admin_headers)
    assert resp.status_code == 200

    resp = client.post(
        "/api/v1/sites",
        headers=admin_headers,
        json={"ville_id": vid, "site": "TEST-SITE", "mail": "a@b.c"},
    )
    assert resp.status_code in (200, 201)
    sid = resp.get_json()["data"]["id"]

    resp = client.patch(
        f"/api/v1/sites/{sid}",
        headers=admin_headers,
        json={"adresse": "Nouvelle adresse"},
    )
    assert resp.status_code == 200

    resp = client.delete(f"/api/v1/sites/{sid}", headers=admin_headers)
    assert resp.status_code == 200


def test_users_list_and_me(client, admin_headers):
    resp = client.get("/api/v1/users/me", headers=admin_headers)
    assert resp.status_code == 200
    assert resp.get_json()["data"]["username"] == "admin@test.local"

    resp = client.get("/api/v1/users", headers=admin_headers)
    assert resp.status_code == 200
    assert len(resp.get_json()["data"]) >= 3


def test_users_create_update(client, admin_headers):
    resp = client.post(
        "/api/v1/users",
        headers=admin_headers,
        json={
            "username": "newuser@test.local",
            "password": "NewUser12!",
            "role_id": 4,
            "site_id": 3,
            "nom": "Nouveau",
            "prenom": "User",
            "etat": "Actif",
        },
    )
    assert resp.status_code in (200, 201), resp.get_json()
    uid = resp.get_json()["data"]["id"]

    resp = client.patch(
        f"/api/v1/users/{uid}",
        headers=admin_headers,
        json={"poste": "Cadre"},
    )
    assert resp.status_code == 200
    assert resp.get_json()["data"]["poste"] == "Cadre"


def test_roles_forbidden_for_agent(client, agent_headers):
    resp = client.get("/api/v1/roles", headers=agent_headers)
    assert resp.status_code == 403
