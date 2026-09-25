import { useCallback, useEffect, useMemo, useState } from 'react';
import { parcApi } from '../../api/endpoints';
import { getErrorMessage, unwrapList } from '../../api/client';
import type { DemandeVoiture } from '../../types';
import { DataTable, type Column } from '../../components/ui/DataTable';
import { Button } from '../../components/ui/Button';
import { Alert } from '../../components/ui/Alert';
import { StatusBadge } from '../../components/ui/StatusBadge';
import { Modal } from '../../components/ui/Modal';
import { PageHeader } from '../../components/layout/PageHeader';
import { EntityLink } from '../../components/ui/EntityLink';

function demandeurLabel(r: DemandeVoiture) {
  const n = [r.user?.nom, r.user?.prenom].filter(Boolean).join(' ').trim();
  return n || (r.user_id ? `#${r.user_id}` : '—');
}

function statusLabel(r: DemandeVoiture) {
  return r.status_demande || r.statut || r.etat || '';
}

export function RequetesPage() {
  const [rows, setRows] = useState<DemandeVoiture[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [q, setQ] = useState('');
  const [detail, setDetail] = useState<DemandeVoiture | null>(null);

  const load = useCallback(async () => {
    setLoading(true);
    try {
      const { data } = await parcApi.listRequetes();
      setRows(unwrapList<DemandeVoiture>(data));
    } catch (err) {
      setError(getErrorMessage(err));
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    void load();
  }, [load]);

  const filtered = useMemo(() => {
    const s = q.trim().toLowerCase();
    if (!s) return rows;
    return rows.filter((r) =>
      [
        String(r.id),
        demandeurLabel(r),
        r.marque,
        r.modele,
        r.categorie_vr,
        r.livraison,
        statusLabel(r),
      ]
        .filter(Boolean)
        .join(' ')
        .toLowerCase()
        .includes(s),
    );
  }, [rows, q]);

  const columns: Column<DemandeVoiture>[] = [
    { key: 'id', header: '#', render: (r) => r.id },
    {
      key: 'demandeur',
      header: 'Demandeur',
      render: (r) =>
        r.user?.id || r.user_id ? (
          <EntityLink to={`/users/${r.user?.id || r.user_id}`}>
            {demandeurLabel(r)}
          </EntityLink>
        ) : (
          demandeurLabel(r)
        ),
    },
    { key: 'marque', header: 'Marque', render: (r) => r.marque || '—' },
    { key: 'modele', header: 'Modèle', render: (r) => r.modele || '—' },
    {
      key: 'duree',
      header: 'Durée (mois)',
      render: (r) => (r.duree_mois != null ? r.duree_mois : '—'),
    },
    { key: 'cat', header: 'Catégorie', render: (r) => r.categorie_vr || '—' },
    {
      key: 'livraison',
      header: 'Livraison',
      render: (r) => r.livraison?.slice(0, 10) || '—',
    },
    {
      key: 'statut',
      header: 'Statut',
      render: (r) => <StatusBadge status={statusLabel(r)} />,
    },
    {
      key: 'created',
      header: 'Créée le',
      render: (r) => (r.created ? r.created.replace('T', ' ').slice(0, 19) : '—'),
    },
    {
      key: 'actions',
      header: 'Actions',
      className: 'actions',
      render: (r) => (
        <span className="actions" onClick={(e) => e.stopPropagation()}>
          <a
            href="#voir"
            onClick={(e) => {
              e.preventDefault();
              setDetail(r);
            }}
          >
            Voir
          </a>
        </span>
      ),
    },
  ];

  return (
    <>
      <PageHeader
        title="Requêtes collaborateurs"
        subtitle="Consultez et traitez les requêtes liées au parc."
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
              value={q}
              onChange={(e) => setQ(e.target.value)}
            />
            <button type="button" className="btn btn-primary-rounded">
              <i className="fa-solid fa-magnifying-glass" /> Rechercher
            </button>
          </div>
        </div>
      </div>
      {error && <Alert variant="danger">{error}</Alert>}
      <DataTable columns={columns} rows={filtered} rowKey={(r) => r.id} loading={loading} />
      <Modal
        open={!!detail}
        title={`Requête #${detail?.id ?? ''}`}
        onClose={() => setDetail(null)}
        size="lg"
        footer={
          <Button variant="secondary" onClick={() => setDetail(null)}>
            Fermer
          </Button>
        }
      >
        {detail && (
          <table className="table table-striped">
            <tbody>
              <tr>
                <th>Demandeur</th>
                <td>{demandeurLabel(detail)}</td>
              </tr>
              <tr>
                <th>Marque</th>
                <td>{detail.marque || '—'}</td>
              </tr>
              <tr>
                <th>Modèle</th>
                <td>{detail.modele || '—'}</td>
              </tr>
              <tr>
                <th>Durée (mois)</th>
                <td>{detail.duree_mois ?? '—'}</td>
              </tr>
              <tr>
                <th>Catégorie</th>
                <td>{detail.categorie_vr || '—'}</td>
              </tr>
              <tr>
                <th>Livraison</th>
                <td>{detail.livraison?.slice(0, 10) || '—'}</td>
              </tr>
              <tr>
                <th>Statut</th>
                <td>
                  <StatusBadge status={statusLabel(detail)} />
                </td>
              </tr>
              <tr>
                <th>Description</th>
                <td>{detail.description || '—'}</td>
              </tr>
              <tr>
                <th>Créée le</th>
                <td>{detail.created ? detail.created.replace('T', ' ').slice(0, 19) : '—'}</td>
              </tr>
            </tbody>
          </table>
        )}
      </Modal>
    </>
  );
}
