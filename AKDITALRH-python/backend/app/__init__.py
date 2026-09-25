"""Factory Flask create_app()."""
import logging
import os
from pathlib import Path

from flask import jsonify

from app.api import register_blueprints
from app.config import Config, TestingConfig
from app.extensions import cors, db, jwt, limiter, mail


def create_app(config_object=None):
    """Crée et configure l'application Flask."""
    from flask import Flask

    app = Flask(__name__)

    if config_object is None:
        if os.getenv("TESTING") == "1" or os.getenv("FLASK_ENV") == "testing":
            config_object = TestingConfig
        else:
            config_object = Config

    app.config.from_object(config_object)

    # Storage
    Path(app.config.get("STORAGE_PATH", "./storage")).mkdir(parents=True, exist_ok=True)

    # Extensions
    db.init_app(app)
    jwt.init_app(app)
    mail.init_app(app)
    cors.init_app(
        app,
        resources={r"/api/*": {"origins": app.config.get("CORS_ORIGINS", "*")}},
        supports_credentials=True,
    )
    limiter.init_app(app)
    if app.config.get("TESTING") or app.config.get("RATELIMIT_ENABLED") is False:
        limiter.enabled = False

    # Importer les modèles pour metadata SQLAlchemy (sans écraser la var locale `app`)
    from app import models as _models  # noqa: F401

    register_blueprints(app)

    _register_error_handlers(app)
    _configure_logging(app)

    @app.after_request
    def security_headers(response):
        response.headers["X-Content-Type-Options"] = "nosniff"
        response.headers["X-Frame-Options"] = "DENY"
        return response

    return app


def _register_error_handlers(app):
    @app.errorhandler(400)
    def bad_request(err):
        return jsonify({"ok": False, "message": getattr(err, "description", "Bad request")}), 400

    @app.errorhandler(401)
    def unauthorized(err):
        return jsonify({"ok": False, "message": "Non authentifié", "code": "unauthorized"}), 401

    @app.errorhandler(403)
    def forbidden(err):
        return jsonify({"ok": False, "message": "Accès refusé", "code": "forbidden"}), 403

    @app.errorhandler(404)
    def not_found(err):
        return jsonify({"ok": False, "message": "Ressource introuvable", "code": "not_found"}), 404

    @app.errorhandler(405)
    def method_not_allowed(err):
        return jsonify({"ok": False, "message": "Méthode non autorisée"}), 405

    @app.errorhandler(429)
    def rate_limited(err):
        return jsonify({"ok": False, "message": "Trop de requêtes", "code": "rate_limit"}), 429

    @app.errorhandler(500)
    def server_error(err):
        app.logger.exception("Erreur serveur: %s", err)
        return jsonify({"ok": False, "message": "Erreur interne", "code": "server_error"}), 500

    @jwt.invalid_token_loader
    def invalid_token(reason):
        return jsonify({"ok": False, "message": "Token invalide", "details": reason}), 401

    @jwt.unauthorized_loader
    def missing_token(reason):
        return jsonify({"ok": False, "message": "Token manquant", "details": reason}), 401

    @jwt.expired_token_loader
    def expired_token(jwt_header, jwt_payload):
        return jsonify({"ok": False, "message": "Token expiré", "code": "token_expired"}), 401


def _configure_logging(app):
    logging.basicConfig(
        level=logging.DEBUG if app.debug else logging.INFO,
        format="%(asctime)s [%(levelname)s] %(name)s: %(message)s",
    )
