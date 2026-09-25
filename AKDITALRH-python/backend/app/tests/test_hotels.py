"""Tests hôtels, chambres, tarifs, available (plafond)."""


def test_hotels_list(client, admin_headers):
    resp = client.get("/api/v1/hotels", headers=admin_headers)
    assert resp.status_code == 200
    data = resp.get_json()["data"]
    assert any(h["hotel"] == "Hotel Test" for h in data)


def test_hotels_list_includes_ville(client, admin_headers):
    data = client.get("/api/v1/hotels", headers=admin_headers).get_json()["data"]
    hotel = next(h for h in data if h["hotel"] == "Hotel Test")
    assert hotel["ville"] == {"id": 1, "ville": "CASABLANCA"}


def test_create_hotel_with_ville_autre(client, admin_headers):
    resp = client.post(
        "/api/v1/hotels",
        headers=admin_headers,
        json={"hotel": "Hotel Nord", "ville_autre": " tanger ", "etoile": "4"},
    )
    assert resp.status_code == 201, resp.get_json()
    ville = resp.get_json()["data"]["ville"]
    assert ville["ville"] == "TANGER"

    resp = client.post(
        "/api/v1/hotels",
        headers=admin_headers,
        json={"hotel": "Hotel Casa", "ville_autre": "casablanca"},
    )
    assert resp.get_json()["data"]["ville"]["id"] == 1


def test_public_hotel_image(client, app, tmp_path):
    app.config["STORAGE_PATH"] = str(tmp_path)
    (tmp_path / "hotels").mkdir()
    (tmp_path / "hotels" / "photo.jpg").write_bytes(b"\xff\xd8\xff")
    assert client.get("/api/v1/files/public/hotels/photo.jpg").status_code == 200
    assert client.get("/api/v1/files/public/hotels/absent.jpg").status_code == 404
    assert client.get("/api/v1/files/public/uploads/photo.jpg").status_code == 404


def test_hotel_detail_with_chambres(client, admin_headers):
    resp = client.get("/api/v1/hotels/1", headers=admin_headers)
    assert resp.status_code == 200
    data = resp.get_json()["data"]
    assert data["id"] == 1
    assert "chambres" in data


def test_create_hotel_and_chambre(client, admin_headers):
    resp = client.post(
        "/api/v1/hotels",
        headers=admin_headers,
        json={"hotel": "Nouveau Hotel", "ville_id": 1, "etoile": "3"},
    )
    assert resp.status_code in (200, 201), resp.get_json()
    hid = resp.get_json()["data"]["id"]

    resp = client.post(
        f"/api/v1/hotels/{hid}/chambres",
        headers=admin_headers,
        json={
            "nom": "Deluxe",
            "type": "INVITE",
            "prices": [{"prix": "900", "date_debut": "2026-01-01", "date_fin": "2026-12-31"}],
        },
    )
    assert resp.status_code in (200, 201), resp.get_json()
    cid = resp.get_json()["data"]["id"]

    resp = client.post(
        f"/api/v1/hotels/chambres/{cid}/prices",
        headers=admin_headers,
        json={"prix": "950", "date_debut": "2027-01-01", "date_fin": "2027-06-30"},
    )
    assert resp.status_code in (200, 201)


def test_hotels_available_plafond(client, admin_headers):
    resp = client.get(
        "/api/v1/hotels/available",
        headers=admin_headers,
        query_string={"ville_id": 1},
    )
    assert resp.status_code == 200
    body = resp.get_json()["data"]
    assert "hotels" in body
    assert "plafond_hotel" in body
    # Admin plafond 1000 → prix 800 inclus
    assert any(h["id"] == 1 for h in body["hotels"])


def test_update_delete_hotel(client, admin_headers):
    resp = client.post(
        "/api/v1/hotels",
        headers=admin_headers,
        json={"hotel": "Temp Hotel", "ville_id": 1},
    )
    hid = resp.get_json()["data"]["id"]
    resp = client.patch(
        f"/api/v1/hotels/{hid}",
        headers=admin_headers,
        json={"etoile": "5"},
    )
    assert resp.status_code == 200
    resp = client.delete(f"/api/v1/hotels/{hid}", headers=admin_headers)
    assert resp.status_code == 200


def _hotel_with_chambre(client, headers):
    hid = client.post(
        "/api/v1/hotels", headers=headers, json={"hotel": "Hotel Chambres", "ville_id": 1}
    ).get_json()["data"]["id"]
    chambre = client.post(
        f"/api/v1/hotels/{hid}/chambres",
        headers=headers,
        json={
            "nom": "101",
            "type": "Standard",
            "prices": [
                {"prix": "500", "date_debut": "2026-01-01", "date_fin": "2026-06-30"},
                {"prix": "600", "date_debut": "2026-07-01", "date_fin": "2026-12-31"},
            ],
        },
    ).get_json()["data"]
    return hid, chambre


def test_delete_hotel_with_chambres(client, admin_headers):
    hid, _ = _hotel_with_chambre(client, admin_headers)
    resp = client.delete(f"/api/v1/hotels/{hid}", headers=admin_headers)
    assert resp.status_code == 200, resp.get_json()
    assert client.get(f"/api/v1/hotels/{hid}", headers=admin_headers).status_code == 404


def test_delete_chambre_removes_prices(client, admin_headers):
    from app.models.hotel import HotelPrice

    _, chambre = _hotel_with_chambre(client, admin_headers)
    resp = client.delete(f"/api/v1/hotels/chambres/{chambre['id']}", headers=admin_headers)
    assert resp.status_code == 200, resp.get_json()
    assert HotelPrice.query.filter_by(chambre_id=chambre["id"]).count() == 0


def test_sync_chambre_prices(client, admin_headers):
    _, chambre = _hotel_with_chambre(client, admin_headers)
    first, second = chambre["prices"]
    resp = client.put(
        f"/api/v1/hotels/chambres/{chambre['id']}/prices",
        headers=admin_headers,
        json={
            "delete_ids": [second["id"]],
            "prices": [
                {"id": first["id"], "prix": "550", "date_debut": "2026-01-01", "date_fin": "2026-06-30"},
                {"prix": "700", "date_debut": "2027-01-01", "date_fin": "2027-12-31"},
            ],
        },
    )
    assert resp.status_code == 200, resp.get_json()
    body = resp.get_json()
    assert body["message"] == "Les prix ont été sauvegardés et supprimés avec succès."
    prices = {p["id"]: p["prix"] for p in body["data"]["prices"]}
    assert prices[first["id"]] == "550"
    assert sorted(prices.values()) == ["550", "700"]

    resp = client.put(
        f"/api/v1/hotels/chambres/{chambre['id']}/prices",
        headers=admin_headers,
        json={"prices": [], "delete_ids": []},
    )
    assert resp.status_code == 400
