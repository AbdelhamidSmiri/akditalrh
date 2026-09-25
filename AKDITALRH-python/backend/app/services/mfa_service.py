"""MFA TOTP via pyotp."""
import pyotp
import qrcode
import io
import base64


def generate_secret() -> str:
    return pyotp.random_base32()


def provisioning_uri(secret: str, username: str, issuer: str = "Akdital RH") -> str:
    return pyotp.TOTP(secret).provisioning_uri(name=username or "user", issuer_name=issuer)


def qr_data_url(uri: str) -> str:
    """QR code en data URL PNG pour le setup MFA."""
    img = qrcode.make(uri)
    buf = io.BytesIO()
    img.save(buf, format="PNG")
    b64 = base64.b64encode(buf.getvalue()).decode("ascii")
    return f"data:image/png;base64,{b64}"


def verify_totp(secret: str | None, code: str | None, window: int = 1) -> bool:
    if not secret or not code:
        return False
    totp = pyotp.TOTP(secret)
    return bool(totp.verify(str(code).strip(), valid_window=window))


def mfa_required_for_user(user, required_roles: list[str]) -> bool:
    """MFA obligatoire si rôle dans MFA_REQUIRED_ROLES ou déjà activé."""
    if user.totp_enabled:
        return True
    return role_requires_mfa(user, required_roles)


def role_requires_mfa(user, required_roles: list[str] | None = None) -> bool:
    """Le rôle de l'utilisateur impose la MFA (MFA_REQUIRED_ROLES)."""
    if required_roles is None:
        from flask import current_app

        required_roles = current_app.config.get("MFA_REQUIRED_ROLES", [])
    role_name = user.role.role if user.role else None
    return bool(role_name and role_name in (required_roles or []))


def enrollment_required(user, required_roles: list[str] | None = None) -> bool:
    """Rôle soumis à la MFA mais MFA pas encore activée → enrôlement forcé."""
    return role_requires_mfa(user, required_roles) and not user.totp_enabled


def mfa_flags(user) -> dict:
    return {
        "mfa_required": role_requires_mfa(user),
        "mfa_enrollment_required": enrollment_required(user),
    }
