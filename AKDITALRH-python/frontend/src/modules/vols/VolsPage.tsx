import { useCallback, useEffect, useMemo, useState } from 'react';
import { Link } from 'react-router-dom';
import { volsApi } from '../../api/endpoints';
import { getErrorMessage, unwrapList } from '../../api/client';
import type { VolReservation } from '../../types';
import { Alert } from '../../components/ui/Alert';
import { StatusBadge } from '../../components/ui/StatusBadge';
import { PageHeader } from '../../components/layout/PageHeader';

function fmtDate(v?: string | null) {
  if (!v) return '—';
  return v.replace('T', ' ').slice(0, 19);
}

function fmtDay(v?: string | null) {
  if (!v) return '—';
  return v.toString().slice(0, 10);
}

export function VolsPage() {
  const [rows, setRows] = useState<VolReservation[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [query, setQuery] = useState('');

  const load = useCallback(async () => {
    setLoading(true);
    setError('');
    try {
      const { data } = await volsApi.list();
      setRows(unwrapList<VolReservation>(data));
    } catch (err) {
      setError(getErrorMessage(err));
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    void load();
  }, [load]);

  const onDelete = async (id: number) => {
    if (!confirm(`Êtes-vous sûr de vouloir supprimer #${id} ?`)) return;
    try {
      await volsApi.remove(id);
      await load();
    } catch (err) {
      setError(getErrorMessage(err));
    }
  };

  const filtered = useMemo(() => {
    const q = query.trim().toLowerCase();
    if (!q) return rows;
    return rows.filter((r) =>
      [
        r.nom,
        r.prenom,
        r.depart,
        r.destination,
        r.num_odm,
        r.message,
        r.etat,
        r.reponse,
        r.num_vol,
        r.nom_transfer,
        r.tel_transfer,
        r.description_transfer,
        r.site_nom,
        r.site?.site,
      ]
        .filter(Boolean)
        .join(' ')
        .toLowerCase()
        .includes(q),
    );
  }, [rows, query]);

  const colCount = 16;

  return (
    <>
      <PageHeader
        title="Demandes de billets d’avion"
        subtitle="Consultez et traitez les demandes reçues de la part des collaborateurs."
      />

      <div className="col-md-5">
        <div className="search-section">
          <div className="input-group mb-3">
            <span className="input-group-text">
              <i className="fa-solid fa-magnifying-glass" />
            </span>
            <input
              type="text"
              className="form-control"
              id="search_input"
              placeholder="Rechercher"
              value={query}
              onChange={(e) => setQuery(e.target.value)}
            />
            <button className="btn btn-primary-rounded search-btn" type="button">
              <i className="fa-solid fa-magnifying-glass" /> Rechercher
            </button>
          </div>
        </div>
      </div>

      {error && <Alert variant="danger">{error}</Alert>}

      <div className="content-table">
        <table className="table table-akdital">
          <thead>
            <tr>
              <th>Utilisateur</th>
              <th>Site</th>
              <th>Destination</th>
              <th>Date</th>
              <th>Num ODM</th>
              <th>Message</th>
              <th>État</th>
              <th>Réponse</th>
              <th>Date de création</th>
              <th>Numéro de vol</th>
              <th>Transfert</th>
              <th>Nom du transfert</th>
              <th>Date du transfert</th>
              <th>Téléphone du transfert</th>
              <th>Description du transfert</th>
              <th className="actions">Actions</th>
            </tr>
          </thead>
          <tbody>
            {loading ? (
              <tr>
                <td colSpan={colCount} className="empty-table-msg text-center">
                  Chargement…
                </td>
              </tr>
            ) : filtered.length === 0 ? (
              <tr>
                <td colSpan={colCount} className="empty-table-msg text-center">
                  Aucune demande
                </td>
              </tr>
            ) : (
              filtered.map((r) => {
                const userName =
                  r.nom ||
                  r.user?.nom ||
                  [r.prenom, r.nom].filter(Boolean).join(' ') ||
                  '—';
                const siteName = r.site_nom || r.site?.site || '—';
                const hasTransfer = r.transfer === '1' || r.transfer === 'true';

                return (
                  <tr key={r.id}>
                    <td>{userName}</td>
                    <td>{siteName}</td>
                    <td>
                      <div>
                        <span>{r.depart || '—'}</span>{' '}
                        <i className="fa-solid fa-arrow-right" />{' '}
                        <span>{r.destination || '—'}</span>
                      </div>
                    </td>
                    <td>
                      <div>
                        <span>{fmtDay(r.date_aller)}</span>{' '}
                        <i className="fa-solid fa-arrow-right" />{' '}
                        <span>{fmtDay(r.date_retour)}</span>
                      </div>
                    </td>
                    <td>{r.num_odm || '—'}</td>
                    <td>{r.message || '—'}</td>
                    <td>
                      <StatusBadge status={r.etat || r.statut} />
                    </td>
                    <td>{r.reponse || '—'}</td>
                    <td>{fmtDate(r.created || r.date_reponse)}</td>
                    <td>{r.num_vol || '—'}</td>
                    <td>
                      <span className="badge badge-transfer">
                        {hasTransfer ? 'Oui' : 'Non'}
                      </span>
                    </td>
                    <td>{r.nom_transfer || '—'}</td>
                    <td>{fmtDate(r.date_transfer)}</td>
                    <td>{r.tel_transfer || '—'}</td>
                    <td>{r.description_transfer || '—'}</td>
                    <td className="actions">
                      <Link to={`/vols/${r.id}`}>Voir</Link>
                      {' / '}
                      <Link to={`/vols/${r.id}/edit`}>Modifier</Link>
                      {' / '}
                      <a
                        href="#delete"
                        onClick={(e) => {
                          e.preventDefault();
                          void onDelete(r.id);
                        }}
                      >
                        Supprimer
                      </a>
                    </td>
                  </tr>
                );
              })
            )}
          </tbody>
        </table>
      </div>
    </>
  );
}
