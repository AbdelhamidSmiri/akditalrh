"""Stockage fichiers privés hors webroot."""
import os
import secrets
import uuid
from pathlib import Path

from flask import current_app, send_file
from werkzeug.utils import secure_filename


ALLOWED_EXTENSIONS = {
    "png", "jpg", "jpeg", "gif", "webp", "pdf", "doc", "docx", "xls", "xlsx", "csv",
}


def storage_root() -> Path:
    root = Path(current_app.config.get("STORAGE_PATH", "./storage"))
    root.mkdir(parents=True, exist_ok=True)
    return root


def allowed_file(filename: str) -> bool:
    return "." in filename and filename.rsplit(".", 1)[1].lower() in ALLOWED_EXTENSIONS


def save_upload(file_storage, folder: str = "uploads") -> str | None:
    """Sauvegarde un fichier uploadé, retourne le nom stocké."""
    if not file_storage or not file_storage.filename:
        return None
    if not allowed_file(file_storage.filename):
        raise ValueError("Type de fichier non autorisé")

    dest_dir = storage_root() / secure_filename(folder)
    dest_dir.mkdir(parents=True, exist_ok=True)
    ext = file_storage.filename.rsplit(".", 1)[1].lower()
    name = f"{uuid.uuid4().hex}.{ext}"
    path = dest_dir / name
    file_storage.save(path)
    return name


def resolve_path(folder: str, filename: str) -> Path | None:
    safe_folder = secure_filename(folder)
    safe_name = secure_filename(filename)
    if not safe_folder or not safe_name:
        return None
    roots = [storage_root()] + [
        Path(p) for p in current_app.config.get("LEGACY_FILES_PATHS", [])
    ]
    for root in roots:
        root = root.resolve()
        path = (root / safe_folder / safe_name).resolve()
        if str(path).startswith(str(root)) and path.is_file():
            return path
    return None


def is_public_folder(folder: str) -> bool:
    return folder in current_app.config.get("PUBLIC_FILE_FOLDERS", [])


def serve(folder: str, filename: str, as_attachment: bool = False):
    path = resolve_path(folder, filename)
    if path is None:
        return None
    return send_file(path, as_attachment=as_attachment, download_name=filename)
