"""Configuration Flask (dev / prod / tests)."""
import os
from datetime import timedelta
from pathlib import Path

from dotenv import load_dotenv

load_dotenv()


def _bool(value: str | None, default: bool = False) -> bool:
    if value is None:
        return default
    return value.strip().lower() in {"1", "true", "yes", "on"}


class Config:
    """Config de base."""

    SECRET_KEY = os.getenv("SECRET_KEY", "dev-secret-change-me")
    SQLALCHEMY_DATABASE_URI = os.getenv(
        "DATABASE_URL",
        "mysql+pymysql://root:@127.0.0.1:3306/akditalrh",
    )
    SQLALCHEMY_TRACK_MODIFICATIONS = False
    SQLALCHEMY_ENGINE_OPTIONS = {"pool_pre_ping": True}

    JWT_SECRET_KEY = os.getenv("JWT_SECRET_KEY", SECRET_KEY)
    JWT_ACCESS_TOKEN_EXPIRES = timedelta(
        seconds=int(os.getenv("JWT_ACCESS_TOKEN_EXPIRES", "900"))
    )
    JWT_REFRESH_TOKEN_EXPIRES = timedelta(
        seconds=int(os.getenv("JWT_REFRESH_TOKEN_EXPIRES", "604800"))
    )
    JWT_TOKEN_LOCATION = ["headers"]
    JWT_HEADER_NAME = "Authorization"
    JWT_HEADER_TYPE = "Bearer"

    MFA_REQUIRED_ROLES = [
        r.strip()
        for r in os.getenv("MFA_REQUIRED_ROLES", "Admin").split(",")
        if r.strip()
    ]

    MAIL_SERVER = os.getenv("MAIL_SERVER", "")
    MAIL_PORT = int(os.getenv("MAIL_PORT", "587"))
    MAIL_USE_TLS = _bool(os.getenv("MAIL_USE_TLS"), True)
    MAIL_USERNAME = os.getenv("MAIL_USERNAME", "")
    MAIL_PASSWORD = os.getenv("MAIL_PASSWORD", "")
    MAIL_DEFAULT_SENDER = os.getenv("MAIL_DEFAULT_SENDER", "noreply@akdital.ma")

    STORAGE_PATH = os.getenv("STORAGE_PATH", "./storage")
    # Dossiers de fichiers de l'ancienne application CakePHP (lecture seule)
    LEGACY_FILES_PATHS = [
        p.strip()
        for p in os.getenv(
            "LEGACY_FILES_PATHS",
            ",".join(
                str(Path(__file__).resolve().parents[3] / "app" / sub / "files")
                for sub in ("storage", "webroot")
            ),
        ).split(",")
        if p.strip()
    ]
    # Images « vitrine » servies sans authentification (FileStorage::$publicFolders côté Cake)
    PUBLIC_FILE_FOLDERS = ["hotels", "chambres", "appartements"]
    CORS_ORIGINS = [
        o.strip()
        for o in os.getenv(
            "CORS_ORIGINS", "http://localhost:5173,http://127.0.0.1:5173"
        ).split(",")
        if o.strip()
    ]
    TOKEN_MAX_AGE_SECONDS = int(os.getenv("TOKEN_MAX_AGE_SECONDS", "604800"))

    RATELIMIT_DEFAULT = os.getenv("RATELIMIT_DEFAULT", "200 per hour")
    RATELIMIT_STORAGE_URI = os.getenv("RATELIMIT_STORAGE_URI", "memory://")


class TestingConfig(Config):
    """Config tests (SQLite mémoire)."""

    TESTING = True
    SECRET_KEY = "test-secret-key-at-least-32-bytes-long!!"
    JWT_SECRET_KEY = "test-jwt-secret-key-at-least-32-bytes!!"
    SQLALCHEMY_DATABASE_URI = "sqlite:///:memory:"
    SQLALCHEMY_ENGINE_OPTIONS = {}
    JWT_ACCESS_TOKEN_EXPIRES = timedelta(hours=1)
    JWT_REFRESH_TOKEN_EXPIRES = timedelta(days=1)
    MFA_REQUIRED_ROLES = []
    WTF_CSRF_ENABLED = False
    RATELIMIT_ENABLED = False
