"""Helpers de réponses JSON uniformes."""
from flask import jsonify


def success(data=None, message=None, status=200):
    """Réponse succès standard."""
    payload = {"ok": True}
    if message is not None:
        payload["message"] = message
    if data is not None:
        payload["data"] = data
    return jsonify(payload), status


def error(message, status=400, code=None, details=None):
    """Réponse erreur standard."""
    payload = {"ok": False, "message": message}
    if code:
        payload["code"] = code
    if details is not None:
        payload["details"] = details
    return jsonify(payload), status
