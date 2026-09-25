import { FormEvent, useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import { reservationsApi } from '../../api/endpoints';
import { getErrorMessage } from '../../api/client';
import { Alert } from '../../components/ui/Alert';
import { Button } from '../../components/ui/Button';
import { Card } from '../../components/ui/Card';
import { Input } from '../../components/ui/Input';
import { Loading } from '../../components/ui/Loading';
import './PublicPages.css';

export function PublicRejectPage() {
  const { token } = useParams();
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState('');
  const [done, setDone] = useState(false);
  const [motif, setMotif] = useState('');

  useEffect(() => {
    if (!token) return;
    void (async () => {
      try {
        await reservationsApi.publicRejectGet(token);
      } catch (err) {
        setError(getErrorMessage(err, 'Lien invalide ou expiré'));
      } finally {
        setLoading(false);
      }
    })();
  }, [token]);

  const onReject = async (e: FormEvent) => {
    e.preventDefault();
    if (!token) return;
    setSubmitting(true);
    setError('');
    try {
      await reservationsApi.publicReject(token, { motif });
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
      <Card title="Refus de réservation">
        {error && <Alert variant="danger">{error}</Alert>}
        {done ? (
          <Alert variant="success">Le refus a bien été enregistré.</Alert>
        ) : (
          <form onSubmit={onReject} className="stack">
            <Input
              label="Motif (optionnel)"
              value={motif}
              onChange={(e) => setMotif(e.target.value)}
            />
            <Button type="submit" variant="danger" loading={submitting}>
              Confirmer le refus
            </Button>
          </form>
        )}
      </Card>
    </div>
  );
}
