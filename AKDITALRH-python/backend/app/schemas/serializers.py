"""Sérialiseurs / helpers de parsing JSON."""
from datetime import date, datetime


def parse_date(value):
    if value is None or value == "":
        return None
    if isinstance(value, date) and not isinstance(value, datetime):
        return value
    if isinstance(value, datetime):
        return value.date()
    return datetime.strptime(str(value)[:10], "%Y-%m-%d").date()


def parse_datetime(value):
    if value is None or value == "":
        return None
    if isinstance(value, datetime):
        return value
    s = str(value).replace("Z", "")
    for fmt in ("%Y-%m-%d %H:%M:%S", "%Y-%m-%dT%H:%M:%S", "%Y-%m-%d"):
        try:
            return datetime.strptime(s[:19], fmt)
        except ValueError:
            continue
    raise ValueError(f"DateTime invalide: {value}")


def apply_fields(obj, data: dict, fields: list[str], parsers: dict | None = None):
    """Applique les champs présents dans data sur l'objet modèle."""
    parsers = parsers or {}
    for field in fields:
        if field in data:
            val = data[field]
            if field in parsers:
                val = parsers[field](val)
            setattr(obj, field, val)
