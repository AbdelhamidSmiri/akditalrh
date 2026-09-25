import { FormEvent, useEffect, useState } from 'react';
import { Link, Navigate, useNavigate } from 'react-router-dom';
import { authApi } from '../../api/endpoints';
import { getErrorMessage } from '../../api/client';
import { PageHeader } from '../../components/layout/PageHeader';
import { flash } from '../../components/ui/Flash';
import { useAuth } from '../../hooks/useAuth';
import './MfaPages.css';

export function MfaDisablePage() {
  const { user, refreshUser } = useAuth();
  const navigate = useNavigate();
  const [code, setCode] = useState('');
  const [submitting, setSubmitting] = useState(false);

  const profilePath = user ? `/users/${user.id}` : '/dashboard';
  const locked = Boolean(user?.mfa_required);

  useEffect(() => {
    if (locked) {
      flash.error('La MFA est obligatoire pour votre rôle et ne peut pas être désactivée.');
    }
  }, [locked]);

  if (locked) return <Navigate to={profilePath} replace />;

  const onSubmit = async (e: FormEvent) => {
    e.preventDefault();
    setSubmitting(true);
    try {
      await authApi.mfaDisable(code.trim());
      await refreshUser();
      flash.success('Double authentification désactivée.');
      navigate(profilePath, { replace: true });
    } catch (err) {
      flash.error(getErrorMessage(err, 'Code MFA invalide.'));
      setCode('');
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <>
      <PageHeader
        title="Désactiver la double authentification"
        subtitle="Saisissez un code MFA actuel pour confirmer."
      />
      <div className="users form">
        <div className="row">
          <div className="col" />
          <div className="col-8">
            <div className="card view-card">
              <div className="card-body">
                <p>Pour désactiver la double authentification, entrez un code MFA valide.</p>
                <form onSubmit={onSubmit}>
                  <div className="input text">
                    <label htmlFor="UserCode">Code MFA</label>
                    <input
                      id="UserCode"
                      name="code"
                      type="text"
                      inputMode="numeric"
                      autoComplete="one-time-code"
                      maxLength={6}
                      placeholder=" "
                      value={code}
                      onChange={(e) => setCode(e.target.value.replace(/\D/g, ''))}
                      required
                    />
                  </div>
                  <div className="submit-section">
                    <button type="submit" className="btn btn-submit" disabled={submitting}>
                      Désactiver la MFA
                    </button>
                    <Link to={profilePath} className="btn btn-link">
                      Annuler
                    </Link>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div className="col" />
        </div>
      </div>
    </>
  );
}
