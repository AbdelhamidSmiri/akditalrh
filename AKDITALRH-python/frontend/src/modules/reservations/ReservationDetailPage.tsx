import { useEffect, useState } from 'react';
import { Link, useParams } from 'react-router-dom';
import { reservationsApi } from '../../api/endpoints';
import { getErrorMessage } from '../../api/client';
import type { Reservation } from '../../types';
import { Card } from '../../components/ui/Card';
import { Button } from '../../components/ui/Button';
import { Alert } from '../../components/ui/Alert';
import { Loading } from '../../components/ui/Loading';
import { StatusBadge } from '../../components/ui/StatusBadge';
import { PageHeader } from '../../components/layout/PageHeader';

export function ReservationDetailPage() {
  const { id } = useParams();
  const [item, setItem] = useState<Reservation | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    if (!id) return;
    void (async () => {
      setLoading(true);
      try {
        const { data } = await reservationsApi.get(id);
        setItem(data);
      } catch (err) {
        setError(getErrorMessage(err));
      } finally {
        setLoading(false);
      }
    })();
  }, [id]);

  if (loading) return <Loading />;
  if (!item) return <Alert variant="danger">{error || 'Réservation introuvable'}</Alert>;

  return (
    <>
      <PageHeader
        title={`Réservation #${item.id}`}
        subtitle="Détail de la demande de réservation hôtelière."
      />
      <div className="page-toolbar">
        <StatusBadge status={item.statut || item.etat} />
        <Link to="/reservations">
          <Button variant="secondary">Retour</Button>
        </Link>
      </div>
      {error && <Alert variant="danger">{error}</Alert>}
      <Card title="Détails">
        <div className="form-grid">
          <div>
            <span className="muted">Hôtel</span>
            <p>
              {item.hotel_nom ||
                item.hotel?.hotel ||
                item.hotel?.nom ||
                item.chambre?.hotel?.hotel ||
                '—'}
            </p>
          </div>
          <div>
            <span className="muted">Chambre</span>
            <p>
              {item.chambre?.nom ||
                item.chambre?.type ||
                item.chambre?.type_chambre ||
                item.chambre?.designation ||
                item.chambre_id ||
                '—'}
            </p>
          </div>
          <div>
            <span className="muted">Arrivée</span>
            <p>
              {(item.checkin || item.date_arrivee || '—').toString().slice(0, 10)}
            </p>
          </div>
          <div>
            <span className="muted">Départ</span>
            <p>
              {(item.checkout || item.date_depart || '—').toString().slice(0, 10)}
            </p>
          </div>
          <div>
            <span className="muted">ODM</span>
            <p>{item.num_odm || item.odm || '—'}</p>
          </div>
          <div>
            <span className="muted">Commentaire</span>
            <p>{item.message || item.commentaire || '—'}</p>
          </div>
        </div>
      </Card>
    </>
  );
}
