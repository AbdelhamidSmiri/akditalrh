import { useEffect, useState } from 'react';
import { Link, useParams } from 'react-router-dom';
import { volsApi } from '../../api/endpoints';
import { getErrorMessage } from '../../api/client';
import type { VolReservation } from '../../types';
import { Card } from '../../components/ui/Card';
import { Button } from '../../components/ui/Button';
import { Alert } from '../../components/ui/Alert';
import { Loading } from '../../components/ui/Loading';
import { StatusBadge } from '../../components/ui/StatusBadge';
import { PageHeader } from '../../components/layout/PageHeader';

export function VolDetailPage() {
  const { id } = useParams();
  const [item, setItem] = useState<VolReservation | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    if (!id) return;
    void (async () => {
      try {
        const { data } = await volsApi.get(id);
        setItem(data);
      } catch (err) {
        setError(getErrorMessage(err));
      } finally {
        setLoading(false);
      }
    })();
  }, [id]);

  if (loading) return <Loading />;
  if (!item) return <Alert variant="danger">{error || 'Demande introuvable'}</Alert>;

  const passenger =
    [item.prenom, item.nom].filter(Boolean).join(' ') ||
    [item.user?.prenom, item.user?.nom].filter(Boolean).join(' ') ||
    `#${item.id}`;

  return (
    <>
      <PageHeader
        title={`Billet — ${passenger}`}
        subtitle="Détail de la demande de billet d’avion."
      />
      <div className="page-toolbar">
        <StatusBadge status={item.statut || item.etat} />
        <Link to="/vols">
          <Button variant="secondary">Retour</Button>
        </Link>
      </div>
      <Card title="Détails">
        <div className="form-grid">
          <div>
            <span className="muted">Trajet</span>
            <p>
              {item.depart || item.origine || '—'} → {item.destination || '—'}
            </p>
          </div>
          <div>
            <span className="muted">Départ</span>
            <p>{(item.date_aller || item.date_depart || '—').toString().slice(0, 10)}</p>
          </div>
          <div>
            <span className="muted">Retour</span>
            <p>{(item.date_retour || '—').toString().slice(0, 10)}</p>
          </div>
          <div>
            <span className="muted">N° ODM</span>
            <p>{item.num_odm || '—'}</p>
          </div>
          <div>
            <span className="muted">Message</span>
            <p>{item.message || item.commentaire || '—'}</p>
          </div>
        </div>
      </Card>
    </>
  );
}
