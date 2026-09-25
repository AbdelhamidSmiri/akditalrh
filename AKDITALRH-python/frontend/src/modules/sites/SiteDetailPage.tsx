import { useEffect, useState } from 'react';
import { Link, useNavigate, useParams } from 'react-router-dom';
import { sitesApi } from '../../api/endpoints';
import { getErrorMessage } from '../../api/client';
import type { Site } from '../../types';
import { Alert } from '../../components/ui/Alert';
import { Button } from '../../components/ui/Button';
import { Loading } from '../../components/ui/Loading';
import { PageHeader } from '../../components/layout/PageHeader';
import { useAuth } from '../../hooks/useAuth';
import '../../components/ui/EntityLink.css';

export function SiteDetailPage() {
  const { id } = useParams();
  const navigate = useNavigate();
  const { isAdmin } = useAuth();
  const [site, setSite] = useState<Site | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    if (!id) return;
    void (async () => {
      setLoading(true);
      setError('');
      try {
        const { data } = await sitesApi.get(id);
        setSite(data);
      } catch (err) {
        setError(getErrorMessage(err));
        setSite(null);
      } finally {
        setLoading(false);
      }
    })();
  }, [id]);

  if (loading) return <Loading />;
  if (!site) {
    return <Alert variant="danger">{error || 'Site introuvable'}</Alert>;
  }

  return (
    <>
      <PageHeader
        title="Détails du Site"
        subtitle="Consultez les informations du site."
      />
      <div className="page-toolbar">
        <Button variant="secondary" onClick={() => navigate(-1)}>
          Retour
        </Button>
        {isAdmin && (
          <Link to="/admin/sites">
            <Button>Gérer les sites</Button>
          </Link>
        )}
      </div>
      {error && <Alert variant="warning">{error}</Alert>}
      <div className="card view-card">
        <div className="card-body">
          <div className="view-detail-grid">
            <div className="info">
              <label>Ville</label>
              <span>{site.ville?.ville || site.ville_id || '—'}</span>
            </div>
            <div className="info">
              <label>Site</label>
              <span>{site.site || '—'}</span>
            </div>
            <div className="info">
              <label>Adresse</label>
              <span>{site.adresse || '—'}</span>
            </div>
            <div className="info">
              <label>Téléphone</label>
              <span>{site.telephone || '—'}</span>
            </div>
            <div className="info">
              <label>Email</label>
              <span>{site.mail || '—'}</span>
            </div>
            <div className="info">
              <label>Créé le</label>
              <span>
                {site.created ? site.created.replace('T', ' ').slice(0, 19) : '—'}
              </span>
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
