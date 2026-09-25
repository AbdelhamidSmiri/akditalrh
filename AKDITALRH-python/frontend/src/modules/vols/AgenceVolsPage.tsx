import { useCallback, useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { volsApi } from '../../api/endpoints';
import { getErrorMessage, unwrapList } from '../../api/client';
import type { VolReservation } from '../../types';
import { DataTable, type Column } from '../../components/ui/DataTable';
import { Alert } from '../../components/ui/Alert';
import { StatusBadge } from '../../components/ui/StatusBadge';
import { PageHeader } from '../../components/layout/PageHeader';

export function AgenceVolsPage() {
  const [rows, setRows] = useState<VolReservation[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [msg, setMsg] = useState('');

  const load = useCallback(async () => {
    setLoading(true);
    setError('');
    try {
      const { data } = await volsApi.agence();
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

  const act = async (
    id: number,
    action: 'valider' | 'annuler' | 'archive',
  ) => {
    setMsg('');
    setError('');
    try {
      if (action === 'valider') await volsApi.valider(id);
      else if (action === 'annuler') await volsApi.annuler(id);
      else await volsApi.archive(id);
      setMsg(`Action « ${action} » effectuée`);
      await load();
    } catch (err) {
      setError(getErrorMessage(err));
    }
  };

  const columns: Column<VolReservation>[] = [
    {
      key: 'passager',
      header: 'Passager',
      render: (r) => [r.prenom, r.nom].filter(Boolean).join(' ') || '—',
    },
    {
      key: 'trajet',
      header: 'Trajet',
      render: (r) => `${r.origine || '—'} → ${r.destination || '—'}`,
    },
    {
      key: 'depart',
      header: 'Départ',
      render: (r) => r.date_depart?.slice(0, 10) || '—',
    },
    {
      key: 'statut',
      header: 'Statut',
      render: (r) => <StatusBadge status={r.statut || r.etat} />,
    },
    {
      key: 'actions',
      header: 'Action',
      className: 'actions',
      render: (r) => (
        <span className="actions" onClick={(e) => e.stopPropagation()}>
          <Link to={`/vols/${r.id}`}>Voir</Link>
          {' · '}
          <a
            href="#valider"
            onClick={(e) => {
              e.preventDefault();
              void act(r.id, 'valider');
            }}
          >
            Valider
          </a>
          {' · '}
          <a
            href="#annuler"
            onClick={(e) => {
              e.preventDefault();
              void act(r.id, 'annuler');
            }}
          >
            Annuler
          </a>
          {' · '}
          <a
            href="#archive"
            onClick={(e) => {
              e.preventDefault();
              void act(r.id, 'archive');
            }}
          >
            Archiver
          </a>
        </span>
      ),
    },
  ];

  return (
    <>
      <PageHeader
        title="Demandes en cours"
        subtitle="Traitez les demandes de billets d’avion en attente."
      />
      {error && <Alert variant="danger">{error}</Alert>}
      {msg && <Alert variant="success">{msg}</Alert>}
      <DataTable columns={columns} rows={rows} rowKey={(r) => r.id} loading={loading} />
    </>
  );
}
