import { useEffect, useState } from 'react';
import { Link, useNavigate, useParams } from 'react-router-dom';
import { usersApi } from '../../api/endpoints';
import { getErrorMessage } from '../../api/client';
import type { User } from '../../types';
import { Alert } from '../../components/ui/Alert';
import { Button } from '../../components/ui/Button';
import { Loading } from '../../components/ui/Loading';
import { PageHeader } from '../../components/layout/PageHeader';
import { useAuth } from '../../hooks/useAuth';
import { EntityLink } from '../../components/ui/EntityLink';
import '../../components/ui/EntityLink.css';
import '../auth/MfaPages.css';

export function UserDetailPage() {
  const { id } = useParams();
  const navigate = useNavigate();
  const { isAdmin, user: authUser } = useAuth();
  const [user, setUser] = useState<User | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    if (!id) return;
    void (async () => {
      setLoading(true);
      setError('');
      try {
        const { data } = await usersApi.get(id);
        setUser(data);
      } catch (err) {
        setError(getErrorMessage(err));
        setUser(null);
      } finally {
        setLoading(false);
      }
    })();
  }, [id]);

  if (loading) return <Loading />;
  if (!user) {
    return <Alert variant="danger">{error || 'Utilisateur introuvable'}</Alert>;
  }

  return (
    <>
      <PageHeader
        title="Détails de l'utilisateur"
        subtitle="Consultez les informations du collaborateur."
      />
      <div className="page-toolbar">
        <Button variant="secondary" onClick={() => navigate(-1)}>
          Retour
        </Button>
        {isAdmin && (
          <Link to={`/admin/users/${user.id}/edit`}>
            <Button>Éditer</Button>
          </Link>
        )}
      </div>
      {error && <Alert variant="warning">{error}</Alert>}
      <div className="card view-card">
        <div className="card-body">
          <div className="view-detail-grid">
            <div className="info">
              <label>Rôle</label>
              <span>{user.role?.role || user.role_id || '—'}</span>
            </div>
            <div className="info">
              <label>Nom d&apos;utilisateur</label>
              <span>{user.username || '—'}</span>
            </div>
            <div className="info">
              <label>État</label>
              <span>{user.etat || user.statut || '—'}</span>
            </div>
            <div className="info">
              <label>Nom</label>
              <span>{user.nom || '—'}</span>
            </div>
            <div className="info">
              <label>Prénom</label>
              <span>{user.prenom || '—'}</span>
            </div>
            <div className="info">
              <label>Date de création</label>
              <span>
                {user.created ? user.created.replace('T', ' ').slice(0, 19) : '—'}
              </span>
            </div>
            <div className="info">
              <label>Double authentification (MFA)</label>
              <span className={user.totp_enabled ? 'mfa-on' : 'mfa-off'}>
                {user.totp_enabled ? 'Activée' : 'Désactivée'}
              </span>
            </div>
            {user.site_id ? (
              <div className="info">
                <label>Site</label>
                <span>
                  <EntityLink to={`/sites/${user.site_id}`}>
                    {user.site?.site || `#${user.site_id}`}
                  </EntityLink>
                </span>
              </div>
            ) : null}
            {user.poste ? (
              <div className="info">
                <label>Poste</label>
                <span>{user.poste}</span>
              </div>
            ) : null}
            {user.direction ? (
              <div className="info">
                <label>Direction</label>
                <span>{user.direction}</span>
              </div>
            ) : null}
            {user.tel ? (
              <div className="info">
                <label>Téléphone</label>
                <span>{user.tel}</span>
              </div>
            ) : null}
          </div>

          {authUser && authUser.id === user.id && (
            <div className="mfa-profile-actions">
              {user.mfa_required && !user.totp_enabled && (
                <p className="mfa-required-note">
                  La MFA est <strong>obligatoire</strong> pour votre rôle.
                </p>
              )}
              {!user.totp_enabled ? (
                <Link to="/users/mfa-setup" className="btn btn-submit">
                  <i className="fa-solid fa-shield-halved" /> Activer la MFA
                </Link>
              ) : !user.mfa_required ? (
                <Link to="/users/mfa-disable" className="btn btn-outline-danger">
                  Désactiver la MFA
                </Link>
              ) : (
                <p className="mfa-locked-note">
                  La MFA est obligatoire pour votre rôle (désactivation impossible).
                </p>
              )}
            </div>
          )}
        </div>
      </div>
    </>
  );
}
