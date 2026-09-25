"""Tests auth : login, SHA1 upgrade, refresh."""


def test_login_success(client):
    resp = client.post(
        "/api/v1/auth/login",
        json={"username": "admin@test.local", "password": "Admin123!"},
    )
    assert resp.status_code == 200
    body = resp.get_json()
    assert body["ok"] is True
    assert "access_token" in body["data"]
    assert "refresh_token" in body["data"]
    assert body["data"]["user"]["username"] == "admin@test.local"


def test_login_invalid(client):
    resp = client.post(
        "/api/v1/auth/login",
        json={"username": "admin@test.local", "password": "wrong"},
    )
    assert resp.status_code == 401
    assert resp.get_json()["ok"] is False


def test_login_sha1_upgrade(client, app):
    from app.extensions import db
    from app.models.user import User
    from app.services import auth_service

    resp = client.post(
        "/api/v1/auth/login",
        json={"username": "legacy@test.local", "password": "Legacy123!"},
    )
    assert resp.status_code == 200
    body = resp.get_json()
    assert body["ok"] is True
    assert "access_token" in body["data"]

    with app.app_context():
        user = User.query.filter_by(username="legacy@test.local").first()
        assert user.password.startswith("$2")
        ok, needs = auth_service.verify_password("Legacy123!", user.password)
        assert ok and not needs


def test_refresh(client):
    login = client.post(
        "/api/v1/auth/login",
        json={"username": "admin@test.local", "password": "Admin123!"},
    )
    refresh = login.get_json()["data"]["refresh_token"]
    resp = client.post(
        "/api/v1/auth/refresh",
        headers={"Authorization": f"Bearer {refresh}"},
    )
    assert resp.status_code == 200
    assert "access_token" in resp.get_json()["data"]


def test_me(client, admin_headers):
    resp = client.get("/api/v1/users/me", headers=admin_headers)
    assert resp.status_code == 200
    assert resp.get_json()["data"]["username"] == "admin@test.local"


def test_forgot_password(client):
    resp = client.post(
        "/api/v1/auth/forgot-password",
        json={"username": "admin@test.local"},
    )
    assert resp.status_code == 200
    assert resp.get_json()["ok"] is True
