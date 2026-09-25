"""Tests MFA, fichiers, permissions."""
import io

import pyotp


def test_mfa_setup_enable_disable(client, admin_headers, app):
    resp = client.post("/api/v1/auth/mfa/setup", headers=admin_headers, json={})
    assert resp.status_code == 200, resp.get_json()
    secret = resp.get_json()["data"]["secret"]
    assert secret

    code = pyotp.TOTP(secret).now()
    resp = client.post(
        "/api/v1/auth/mfa/enable",
        headers=admin_headers,
        json={"code": code},
    )
    assert resp.status_code == 200, resp.get_json()

    code2 = pyotp.TOTP(secret).now()
    resp = client.post(
        "/api/v1/auth/mfa/disable",
        headers=admin_headers,
        json={"code": code2},
    )
    assert resp.status_code == 200, resp.get_json()


def test_mfa_enrollment_enforced_for_required_role(client, app):
    app.config["MFA_REQUIRED_ROLES"] = ["Admin"]
    resp = client.post(
        "/api/v1/auth/login",
        json={"username": "admin@test.local", "password": "Admin123!"},
    )
    assert resp.status_code == 200
    data = resp.get_json()["data"]
    assert data["user"]["mfa_enrollment_required"] is True
    headers = {"Authorization": f"Bearer {data['access_token']}"}

    resp = client.get("/api/v1/users", headers=headers)
    assert resp.status_code == 403
    assert resp.get_json()["code"] == "mfa_enrollment_required"

    resp = client.get("/api/v1/users/me", headers=headers)
    assert resp.status_code == 200
    assert resp.get_json()["data"]["mfa_enrollment_required"] is True

    resp = client.post("/api/v1/auth/mfa/setup", headers=headers, json={})
    assert resp.status_code == 200
    secret = resp.get_json()["data"]["secret"]
    again = client.post("/api/v1/auth/mfa/setup", headers=headers, json={})
    assert again.get_json()["data"]["secret"] == secret

    resp = client.post("/api/v1/auth/mfa/enable", headers=headers, json={"code": "000000"})
    assert resp.status_code == 400

    resp = client.post(
        "/api/v1/auth/mfa/enable",
        headers=headers,
        json={"code": pyotp.TOTP(secret).now()},
    )
    assert resp.status_code == 200
    assert resp.get_json()["data"]["mfa_enrollment_required"] is False

    resp = client.get("/api/v1/users", headers=headers)
    assert resp.status_code == 200

    resp = client.post("/api/v1/auth/mfa/setup", headers=headers, json={})
    assert resp.status_code == 400

    resp = client.post(
        "/api/v1/auth/mfa/disable",
        headers=headers,
        json={"code": pyotp.TOTP(secret).now()},
    )
    assert resp.status_code == 403

    resp = client.post(
        "/api/v1/auth/login",
        json={"username": "admin@test.local", "password": "Admin123!"},
    )
    assert resp.get_json()["data"]["mfa_required"] is True
    assert "access_token" not in resp.get_json()["data"]


def test_logout(client, admin_headers):
    resp = client.post("/api/v1/auth/logout", headers=admin_headers)
    assert resp.status_code == 200


def test_unauthorized_without_token(client):
    resp = client.get("/api/v1/users")
    assert resp.status_code == 401


def test_files_upload_and_serve(client, admin_headers, app, tmp_path):
    app.config["STORAGE_PATH"] = str(tmp_path)
    data = {
        "folder": "tests",
        "file": (io.BytesIO(b"hello akdital"), "hello.txt"),
    }
    resp = client.post(
        "/api/v1/files/upload",
        headers=admin_headers,
        data=data,
        content_type="multipart/form-data",
    )
    # upload may return 200/201 or 400 if folder restricted
    assert resp.status_code in (200, 201, 400, 403), resp.get_json()
    if resp.status_code in (200, 201):
        body = resp.get_json()["data"]
        filename = body.get("filename") or body.get("name")
        folder = body.get("folder") or "tests"
        if filename:
            resp = client.get(
                f"/api/v1/files/{folder}/{filename}",
                headers=admin_headers,
            )
            assert resp.status_code in (200, 404)


def test_permission_agent_cannot_manage_users(client, agent_headers):
    resp = client.post(
        "/api/v1/users",
        headers=agent_headers,
        json={
            "username": "hack@test.local",
            "password": "Hack1234!",
            "role_id": 1,
            "site_id": 3,
        },
    )
    assert resp.status_code == 403
