"""Journal d'audit structuré (logger)."""
import logging
from datetime import datetime, timezone

logger = logging.getLogger("akdital.audit")


def audit(action: str, user_id: int | None = None, details: dict | None = None):
    logger.info(
        "audit action=%s user_id=%s details=%s at=%s",
        action,
        user_id,
        details or {},
        datetime.now(timezone.utc).isoformat(),
    )
