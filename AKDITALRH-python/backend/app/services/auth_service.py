"""Auth : bcrypt + upgrade SHA1 legacy (compat CakePHP MigratingPasswordHasher)."""
import hashlib
import re

import bcrypt

_SHA1_RE = re.compile(r"^[0-9a-f]{40}$", re.I)
_BCRYPT_RE = re.compile(r"^\$2[aby]\$")


def hash_password(plain: str) -> str:
    """Hash bcrypt ($2b$)."""
    return bcrypt.hashpw(plain.encode("utf-8"), bcrypt.gensalt(rounds=12)).decode("utf-8")


def _normalize_bcrypt(stored: str) -> bytes:
    """PHP utilise $2y$ — bcrypt Python accepte $2b$."""
    if stored.startswith("$2y$"):
        stored = "$2b$" + stored[4:]
    return stored.encode("utf-8")


def verify_password(plain: str, stored: str | None) -> tuple[bool, bool]:
    """
    Vérifie le mot de passe.
    Retourne (ok, needs_rehash) — needs_rehash True si SHA1 legacy.
    """
    if not stored or not plain:
        return False, False

    # Legacy SHA1 hex 40 chars
    if _SHA1_RE.match(stored):
        digest = hashlib.sha1(plain.encode("utf-8")).hexdigest()
        return digest.lower() == stored.lower(), True

    if _BCRYPT_RE.match(stored):
        try:
            ok = bcrypt.checkpw(plain.encode("utf-8"), _normalize_bcrypt(stored))
            return ok, False
        except ValueError:
            return False, False

    return False, False


def upgrade_password_if_needed(user, plain: str) -> bool:
    """Rehash bcrypt après login SHA1 réussi. Retourne True si mis à jour."""
    ok, needs = verify_password(plain, user.password)
    if ok and needs:
        user.password = hash_password(plain)
        return True
    return False


def validate_password_strength(password: str, username: str | None = None) -> list[str]:
    """Règles métier CakePHP : min 8, lettre + chiffre, pas trop proche username."""
    errors = []
    if not password or len(password) < 8:
        errors.append("Le mot de passe doit contenir au moins 8 caractères")
    if not re.search(r"[A-Za-z]", password or ""):
        errors.append("Le mot de passe doit contenir au moins une lettre")
    if not re.search(r"\d", password or ""):
        errors.append("Le mot de passe doit contenir au moins un chiffre")
    if username and username.lower() in (password or "").lower():
        errors.append("Le mot de passe ne doit pas contenir le nom d'utilisateur")
    return errors
