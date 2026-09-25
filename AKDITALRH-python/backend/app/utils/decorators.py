"""Décorateurs JWT + contrôle de rôles (deny by default)."""
from functools import wraps

from flask import g, request
from flask_jwt_extended import get_jwt, get_jwt_identity, verify_jwt_in_request

from app.extensions import db
from app.models.user import User
from app.services import mfa_service
from app.utils.responses import error

MFA_ENROLLMENT_ALLOWED_ENDPOINTS = {
    "api_v1.auth.mfa_setup",
    "api_v1.auth.mfa_enable",
    "api_v1.auth.logout",
    "api_v1.users.me",
}


def jwt_required(fn):
    """Exige un access token JWT valide et charge l'utilisateur courant."""

    @wraps(fn)
    def wrapper(*args, **kwargs):
        verify_jwt_in_request()
        identity = get_jwt_identity()
        try:
            user_id = int(identity)
        except (TypeError, ValueError):
            return error("Token invalide", 401, code="invalid_token")
        user = db.session.get(User, user_id)
        if not user or (user.etat and user.etat.lower() not in {"actif", "active"}):
            return error("Utilisateur inactif ou introuvable", 401, code="inactive")
        if (
            mfa_service.enrollment_required(user)
            and request.endpoint not in MFA_ENROLLMENT_ALLOWED_ENDPOINTS
        ):
            return error(
                "La double authentification (MFA) est obligatoire pour votre compte. "
                "Veuillez l’activer.",
                403,
                code="mfa_enrollment_required",
            )
        g.current_user = user
        g.jwt_claims = get_jwt()
        return fn(*args, **kwargs)

    return wrapper


def role_required(*roles):
    """
    Autorise uniquement les rôles listés.
    Admin a toujours accès. Deny by default si aucun rôle match.
    """

    def decorator(fn):
        @wraps(fn)
        @jwt_required
        def wrapper(*args, **kwargs):
            user = g.current_user
            role_name = user.role.role if user.role else None
            if role_name == "Admin":
                return fn(*args, **kwargs)
            if roles and role_name in roles:
                return fn(*args, **kwargs)
            return error("Accès refusé", 403, code="forbidden")

        return wrapper

    return decorator


def owner_or_roles(*roles, owner_attr="user_id"):
    """Admin / rôles listés OU propriétaire de la ressource (kwargs id)."""

    def decorator(fn):
        @wraps(fn)
        @jwt_required
        def wrapper(*args, **kwargs):
            user = g.current_user
            role_name = user.role.role if user.role else None
            if role_name == "Admin" or (roles and role_name in roles):
                return fn(*args, **kwargs)
            # Le contrôle owner se fait dans la vue via g.current_user
            g._owner_check = owner_attr
            return fn(*args, **kwargs)

        return wrapper

    return decorator
