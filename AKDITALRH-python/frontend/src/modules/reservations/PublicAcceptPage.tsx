import { FormEvent, useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import { reservationsApi } from '../../api/endpoints';
import { getErrorMessage } from '../../api/client';
import { Alert } from '../../components/ui/Alert';
import { Button } from '../../components/ui/Button';
import { Card } from '../../components/ui/Card';
import { Loading } from '../../components/ui/Loading';
import './PublicPages.css';

export function PublicAcceptPage() {
  const { token } = useParams();
  const [info, setInfo] = useState<Record<string, unknown> | null>(null);
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState('');
  const [done, setDone] = useState(false);

  useEffect(() => {
    if (!token) return;
    void (async () => {
      try {
        const { data } = await reservationsApi.publicAcceptGet(token);
        setInfo(data as Record<string, unknown>);
      } catch (err) {
        setError(getErrorMessage(err, 'Lien invalide ou expiré'));
      } finally {
        setLoading(false);
      }
    })();
  }, [token]);

  const onAccept = async (e: FormEvent) => {
    e.preventDefault();
    if (!token) return;
    setSubmitting(true);
    setError('');
    try {
      await reservationsApi.publicAccept(token);
      setDone(true);
    } catch (err) {
      setError(getErrorMessage(err));
    } finally {
      setSubmitting(false);
    }
  };

  if (loading) return <Loading fullPage />;

  return (
    <div className="public-page">
      <Card title="Confirmation de réservation">
        {error && <Alert variant="danger">{error}</Alert>}
        {done ? (
          <Alert variant="success">
            Réservation acceptée. Merci pour votre confirmation.
          </Alert>
        ) : (
          <form onSubmit={onAccept} className="stack">
            {info && (
              <p className="muted">
                Vous êtes sur le point d&apos;accepter la réservation
                {info.id ? ` #${String(info.id)}` : ''}.
              </p>
            )}
            <Button type="submit" variant="teal" loading={submitting}>
              Confirmer l&apos;acceptation
            </Button>
          </form>
        )}
      </Card>
    </div>
  );
}
