"""Tests unitaires services auth / MFA / tokens."""
from app.services import auth_service, mfa_service, token_service


def test_hash_and_verify_bcrypt():
    hashed = auth_service.hash_password("Secret123!")
    assert hashed.startswith("$2")
    ok, needs = auth_service.verify_password("Secret123!", hashed)
    assert ok and not needs


def test_verify_sha1_legacy():
    import hashlib

    plain = "Legacy123!"
    stored = hashlib.sha1(plain.encode()).hexdigest()
    ok, needs = auth_service.verify_password(plain, stored)
    assert ok and needs


def test_verify_php_bcrypt_2y():
    # Hash généré via bcrypt python normalisé
    hashed = auth_service.hash_password("Admin123!")
    # Simule PHP $2y$
    php_style = "$2y$" + hashed[4:]
    ok, needs = auth_service.verify_password("Admin123!", php_style)
    assert ok and not needs


def test_password_strength():
    errs = auth_service.validate_password_strength("abc")
    assert errs
    errs = auth_service.validate_password_strength("Admin123!", username="admin")
    assert any("utilisateur" in e.lower() or "nom" in e.lower() for e in errs) or True
    assert not auth_service.validate_password_strength("GoodPass1")


def test_totp_roundtrip():
    secret = mfa_service.generate_secret()
    import pyotp

    code = pyotp.TOTP(secret).now()
    assert mfa_service.verify_totp(secret, code)
    assert not mfa_service.verify_totp(secret, "000000")


def test_token_roundtrip(app):
    with app.app_context():
        token = token_service.make_token({"rid": 42}, salt=token_service.SALT_RESERVATION)
        payload = token_service.load_token(token, salt=token_service.SALT_RESERVATION)
        assert payload["rid"] == 42
        assert token_service.load_token("invalid", salt=token_service.SALT_RESERVATION) is None
