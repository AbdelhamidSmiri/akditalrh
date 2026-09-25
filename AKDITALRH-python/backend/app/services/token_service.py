"""Tokens signés itsdangerous (liens email publics)."""
from flask import current_app
from itsdangerous import BadSignature, SignatureExpired, URLSafeTimedSerializer


def _serializer(salt: str = "akdital-secure-token") -> URLSafeTimedSerializer:
    return URLSafeTimedSerializer(current_app.config["SECRET_KEY"], salt=salt)


def make_token(payload: dict, salt: str = "akdital-secure-token") -> str:
    return _serializer(salt).dumps(payload)


def load_token(token: str, salt: str = "akdital-secure-token", max_age: int | None = None) -> dict | None:
    max_age = max_age if max_age is not None else current_app.config.get("TOKEN_MAX_AGE_SECONDS", 604800)
    try:
        return _serializer(salt).loads(token, max_age=max_age)
    except SignatureExpired:
        return None
    except BadSignature:
        return None


# Salts dédiés
SALT_RESERVATION = "reservation-action"
SALT_BENEFICIAIRE = "beneficiaire-conditions"
SALT_RESET_PASSWORD = "password-reset"
SALT_MFA_PENDING = "mfa-pending"
