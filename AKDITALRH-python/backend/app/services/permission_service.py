"""Matrice de permissions (deny by default)."""

# Ressources → rôles autorisés (hors Admin qui a tout)
PERMISSIONS = {
    "users": {"read": ["Admin", "DRH", "RH"], "write": ["Admin"]},
    "roles": {"read": ["Admin"], "write": ["Admin"]},
    "sites": {"read": ["Admin", "DRH", "RH", "Achat", "DRACHAT", "DGD"], "write": ["Admin"]},
    "villes": {"read": ["Admin", "DRH", "RH", "Achat", "DRACHAT", "DGD", "Directeur de projet"], "write": ["Admin"]},
    "hotels": {
        "read": ["Admin", "DRH", "RH", "Achat", "DRACHAT", "DGD", "Directeur de projet"],
        "write": ["Admin", "Achat", "DRACHAT"],
    },
    "reservations": {
        "read": ["Admin", "DRH", "RH", "Achat", "DRACHAT", "DGD", "Directeur de projet"],
        "write": ["Admin", "DRH", "RH", "Directeur de projet", "Achat", "DGD"],
    },
    "vols": {
        "read": ["Admin", "Agence", "DRH", "RH", "Directeur de projet", "Achat", "DGD"],
        "write": ["Admin", "DRH", "RH", "Directeur de projet"],
        "agence": ["Admin", "Agence"],
    },
    "logement": {
        "read": ["Admin", "DRH", "RH", "Directeur de projet"],
        "write": ["Admin", "DRH", "RH"],
    },
    "parc": {
        "read": ["Admin", "Achat", "DRACHAT", "DRH", "RH", "DGD"],
        "write": ["Admin", "Achat", "DRACHAT"],
    },
    "carburant": {
        "read": ["Admin", "Achat", "DRACHAT", "DRH", "RH"],
        "write": ["Admin", "Achat", "DRACHAT"],
    },
    "jawaz": {
        "read": ["Admin", "Achat", "DRACHAT", "DRH", "RH"],
        "write": ["Admin", "Achat", "DRACHAT"],
    },
    "files": {
        "read": ["Admin", "Agence", "DRH", "RH", "Achat", "DRACHAT", "DGD", "Directeur de projet"],
        "write": ["Admin", "Agence", "DRH", "RH", "Achat", "DRACHAT", "DGD", "Directeur de projet"],
    },
    "dashboard": {
        "read": ["Admin", "Agence", "DRH", "RH", "Achat", "DRACHAT", "DGD", "Directeur de projet"],
    },
}


def role_name(user) -> str | None:
    return user.role.role if user and user.role else None


def can(user, resource: str, action: str = "read") -> bool:
    name = role_name(user)
    if not name:
        return False
    if name == "Admin":
        return True
    perms = PERMISSIONS.get(resource, {})
    allowed = perms.get(action, [])
    return name in allowed


def is_admin(user) -> bool:
    return role_name(user) == "Admin"


def is_agence(user) -> bool:
    return role_name(user) == "Agence"
