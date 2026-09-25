"""Upload / serve / download fichiers privés."""
from flask import Blueprint, g, request

from app.services import permission_service, storage_service
from app.utils.decorators import jwt_required
from app.utils.responses import error, success

bp = Blueprint("files", __name__)


@bp.post("/upload")
@jwt_required
def upload():
    if not permission_service.can(g.current_user, "files", "write"):
        return error("Accès refusé", 403)
    f = request.files.get("file")
    folder = request.form.get("folder") or request.args.get("folder") or "uploads"
    if not f:
        return error("Fichier requis (file)", 400)
    try:
        name = storage_service.save_upload(f, folder=folder)
    except ValueError as exc:
        return error(str(exc), 400)
    return success(
        {
            "folder": folder,
            "filename": name,
            "url": f"/api/v1/files/{folder}/{name}",
            "download_url": f"/api/v1/files/{folder}/{name}/download",
        },
        status=201,
    )


@bp.get("/public/<folder>/<filename>")
def serve_public(folder, filename):
    """Images hôtels / chambres / appartements (lisibles dans une balise <img> sans JWT)."""
    if not storage_service.is_public_folder(folder):
        return error("Fichier introuvable", 404)
    resp = storage_service.serve(folder, filename, as_attachment=False)
    if resp is None:
        return error("Fichier introuvable", 404)
    resp.headers["Cache-Control"] = "public, max-age=86400"
    return resp


@bp.get("/<folder>/<filename>")
@jwt_required
def serve(folder, filename):
    if not permission_service.can(g.current_user, "files", "read"):
        return error("Accès refusé", 403)
    resp = storage_service.serve(folder, filename, as_attachment=False)
    if resp is None:
        return error("Fichier introuvable", 404)
    return resp


@bp.get("/<folder>/<filename>/download")
@jwt_required
def download(folder, filename):
    if not permission_service.can(g.current_user, "files", "read"):
        return error("Accès refusé", 403)
    resp = storage_service.serve(folder, filename, as_attachment=True)
    if resp is None:
        return error("Fichier introuvable", 404)
    return resp
