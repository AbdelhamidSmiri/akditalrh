import { useCallback, useEffect, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { reservationsApi } from '../../api/endpoints';
import { getErrorMessage, unwrapList } from '../../api/client';
import type { Reservation } from '../../types';
import { DataTable, type Column } from '../../components/ui/DataTable';
import { Button } from '../../components/ui/Button';
import { Alert } from '../../components/ui/Alert';
import { StatusBadge } from '../../components/ui/StatusBadge';
import { PageHeader } from '../../components/layout/PageHeader';

export function ReservationsPage() {
  const navigate = useNavigate();
  const [rows, setRows] = useState<Reservation[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  const load = useCallback(async () => {
    setLoading(true);
    setError('');
    try {
      const { data } = await reservationsApi.list();
      setRows(unwrapList<Reservation>(data));
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
    if (!confirm('Supprimer cette réservation ?')) return;
    try {
      await reservationsApi.remove(id);
      await load();
    } catch (err) {
      setError(getErrorMessage(err));
    }
  };

  const columns: Column<Reservation>[] = [
    { key: 'id', header: 'ID', render: (r) => r.id },
    {
      key: 'hotel',
      header: 'Hôtel',
      render: (r) =>
        r.hotel_nom ||
        r.hotel?.hotel ||
        r.hotel?.nom ||
        r.chambre?.hotel?.hotel ||
        r.chambre?.hotel?.nom ||
        r.chambre?.nom ||
        '—',
    },
    {
      key: 'dates',
      header: 'Séjour',
      render: (r) =>
        `${(r.checkin || r.date_arrivee || '—').toString().slice(0, 10)} → ${(
          r.checkout ||
          r.date_depart ||
          '—'
        )
          .toString()
          .slice(0, 10)}`,
    },
    { key: 'odm', header: 'ODM', render: (r) => r.num_odm || r.odm || '—' },
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
          <Link to={`/reservations/${r.id}`}>Voir</Link>
          {' · '}
          <a
            href="#delete"
            onClick={(e) => {
              e.preventDefault();
              void onDelete(r.id);
            }}
          >
            Supprimer
          </a>
        </span>
      ),
    },
  ];

  return (
    <>
      <PageHeader
        title="Réservations d’hôtels"
        subtitle="Consultez et gérez les demandes d’hébergement."
      />
      <div className="page-toolbar">
        <Link to="/reservations/new">
          <Button>Demande d’hôtel</Button>
        </Link>
      </div>
      {error && <Alert variant="danger">{error}</Alert>}
      <DataTable
        columns={columns}
        rows={rows}
        rowKey={(r) => r.id}
        loading={loading}
        onRowClick={(r) => navigate(`/reservations/${r.id}`)}
      />
    </>
  );
}
