"""Authentification JWT + MFA + reset password."""
from flask import Blueprint, g, request
from flask_jwt_extended import (
    create_access_token,
    create_refresh_token,
    get_jwt_identity,
    jwt_required as flask_jwt_required,
)

from app.extensions import db, limiter
from app.models.user import User
from app.services import auth_service, audit_service, mail_service, mfa_service, token_service
from app.utils.decorators import jwt_required
from app.utils.responses import error, success

bp = Blueprint("auth", __name__)

INVALID_CREDENTIALS_MESSAGE = "E-mail ou mot de passe incorrect. Veuillez réessayer."


def _issue_tokens(user: User):
    claims = {
        "role": user.role.role if user.role else None,
        "role_id": user.role_id,
        "username": user.username,
    }
    access = create_access_token(identity=str(user.id), additional_claims=claims)
    refresh = create_refresh_token(identity=str(user.id), additional_claims=claims)
    return {
        "access_token": access,
        "refresh_token": refresh,
        "token_type": "Bearer",
        "user": {**user.to_dict(), **mfa_service.mfa_flags(user)},
    }


@bp.post("/login")
@limiter.limit("10 per minute")
def login():
    data = request.get_json(silent=True) or {}
    username = (data.get("username") or "").strip()
    password = data.get("password") or ""
    if not username or not password:
        return error("username et password requis", 400)

    user = User.query.filter_by(username=username).first()
    if not user:
        audit_service.audit("login_failed", details={"username": username})
        return error(INVALID_CREDENTIALS_MESSAGE, 401, code="invalid_credentials")

    ok, _ = auth_service.verify_password(password, user.password)
    if not ok:
        audit_service.audit("login_failed", user_id=user.id)
        return error(INVALID_CREDENTIALS_MESSAGE, 401, code="invalid_credentials")

    if auth_service.upgrade_password_if_needed(user, password):
        db.session.commit()
        audit_service.audit("password_upgraded_sha1", user_id=user.id)

    if user.totp_enabled and user.totp_secret:
        mfa_token = token_service.make_token(
            {"uid": user.id, "purpose": "mfa"},
            salt=token_service.SALT_MFA_PENDING,
        )
        return success(
            {
                "mfa_required": True,
                "mfa_token": mfa_token,
                "totp_enabled": True,
                "setup_required": False,
            }
        )

    audit_service.audit("login_success", user_id=user.id)
    if mfa_service.enrollment_required(user):
        audit_service.audit("mfa_enrollment_enforced", user_id=user.id)
    return success(_issue_tokens(user))


@bp.post("/mfa/verify")
@limiter.limit("20 per minute")
def mfa_verify():
    data = request.get_json(silent=True) or {}
    mfa_token = data.get("mfa_token")
    code = data.get("code") or data.get("totp")
    payload = token_service.load_token(
        mfa_token or "",
        salt=token_service.SALT_MFA_PENDING,
        max_age=600,
    )
    if not payload or payload.get("purpose") != "mfa":
        return error("mfa_token invalide ou expiré", 401, code="invalid_mfa_token")

    user = db.session.get(User, payload["uid"])
    if not user:
        return error("Utilisateur introuvable", 404)

    if not user.totp_secret:
        return error("MFA non configuré — utilisez /mfa/setup", 400, code="mfa_setup_required")

    if not mfa_service.verify_totp(user.totp_secret, code):
        audit_service.audit("mfa_failed", user_id=user.id)
        return error("Code MFA invalide", 401, code="invalid_totp")

    if not user.totp_enabled:
        user.totp_enabled = True
        db.session.commit()

    audit_service.audit("mfa_success", user_id=user.id)
    return success(_issue_tokens(user))


@bp.post("/mfa/setup")
@jwt_required
def mfa_setup():
    """Démarre l'enrôlement MFA : QR code + clé secrète (secret conservé jusqu'à l'activation)."""
    user = g.current_user
    if user.totp_enabled:
        return error("La double authentification est déjà activée.", 400, code="mfa_already_enabled")

    if not user.totp_secret:
        user.totp_secret = mfa_service.generate_secret()
        db.session.commit()

    secret = user.totp_secret
    uri = mfa_service.provisioning_uri(secret, user.username or f"user-{user.id}")
    return success(
        {
            "secret": secret,
            "otpauth_uri": uri,
            "qr_data_url": mfa_service.qr_data_url(uri),
        }
    )


@bp.post("/mfa/enable")
@jwt_required
def mfa_enable():
    data = request.get_json(silent=True) or {}
    user = g.current_user
    code = data.get("code") or data.get("totp")
    if not user.totp_secret or not mfa_service.verify_totp(user.totp_secret, code):
        return error("Code invalide. Rescannez le QR et réessayez.", 400, code="invalid_totp")
    user.totp_enabled = True
    db.session.commit()
    audit_service.audit("mfa_enabled", user_id=user.id)
    return success(
        {**user.to_dict(), **mfa_service.mfa_flags(user)},
        message="Double authentification activée.",
    )


@bp.post("/mfa/disable")
@jwt_required
def mfa_disable():
    data = request.get_json(silent=True) or {}
    user = g.current_user
    if mfa_service.role_requires_mfa(user):
        return error(
            "La MFA est obligatoire pour votre rôle et ne peut pas être désactivée.",
            403,
            code="mfa_required_for_role",
        )
    code = data.get("code") or data.get("totp")
    if user.totp_enabled and not mfa_service.verify_totp(user.totp_secret, code):
        return error("Code MFA invalide.", 400, code="invalid_totp")
    user.totp_enabled = False
    user.totp_secret = None
    db.session.commit()
    audit_service.audit("mfa_disabled", user_id=user.id)
    return success(
        {**user.to_dict(), **mfa_service.mfa_flags(user)},
        message="Double authentification désactivée.",
    )


@bp.post("/refresh")
@flask_jwt_required(refresh=True)
def refresh():
    identity = get_jwt_identity()
    user = db.session.get(User, int(identity))
    if not user:
        return error("Utilisateur introuvable", 404)
    return success(_issue_tokens(user))


@bp.post("/logout")
@jwt_required
def logout():
    audit_service.audit("logout", user_id=g.current_user.id)
    return success(message="Déconnecté")


@bp.post("/forgot-password")
@limiter.limit("5 per minute")
def forgot_password():
    data = request.get_json(silent=True) or {}
    username = (data.get("username") or data.get("email") or "").strip()
    if username:
        user = User.query.filter_by(username=username).first()
        if user:
            token = token_service.make_token(
                {"uid": user.id, "purpose": "reset"},
                salt=token_service.SALT_RESET_PASSWORD,
            )
            mail_service.send_mail(
                subject="Réinitialisation mot de passe — Akdital RH",
                recipients=[username] if "@" in username else [],
                body=f"Token de réinitialisation : {token}",
            )
            audit_service.audit("forgot_password", user_id=user.id)
    return success(message="Si le compte existe, un email a été envoyé")


@bp.post("/reset-password")
def reset_password():
    data = request.get_json(silent=True) or {}
    token = data.get("token")
    password = data.get("password") or ""
    payload = token_service.load_token(token or "", salt=token_service.SALT_RESET_PASSWORD)
    if not payload or payload.get("purpose") != "reset":
        return error("Token invalide ou expiré", 400)
    user = db.session.get(User, payload["uid"])
    if not user:
        return error("Utilisateur introuvable", 404)
    errs = auth_service.validate_password_strength(password, user.username)
    if errs:
        return error("; ".join(errs), 400)
    user.password = auth_service.hash_password(password)
    db.session.commit()
    return success(message="Mot de passe mis à jour")
