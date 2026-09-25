"""Envoi d'emails — log si SMTP non configuré."""
import logging

from flask import current_app
from flask_mail import Message

from app.extensions import mail

logger = logging.getLogger(__name__)


def send_mail(subject: str, recipients: list[str], body: str, html: str | None = None) -> bool:
    recipients = [r.strip() for r in recipients if r and r.strip()]
    if not recipients:
        logger.warning("Aucun destinataire pour: %s", subject)
        return False

    server = current_app.config.get("MAIL_SERVER") or ""
    if not server:
        logger.info(
            "[MAIL désactivé] subject=%s to=%s body=%s",
            subject,
            recipients,
            body[:500],
        )
        return True

    msg = Message(
        subject=subject,
        recipients=recipients,
        body=body,
        html=html,
        sender=current_app.config.get("MAIL_DEFAULT_SENDER"),
    )
    try:
        mail.send(msg)
        return True
    except Exception as exc:  # noqa: BLE001
        logger.exception("Échec envoi mail: %s", exc)
        return False


def parse_multi_emails(raw: str | None) -> list[str]:
    """Mails hôtel séparés par ;"""
    if not raw:
        return []
    return [e.strip() for e in raw.replace(",", ";").split(";") if e.strip()]
