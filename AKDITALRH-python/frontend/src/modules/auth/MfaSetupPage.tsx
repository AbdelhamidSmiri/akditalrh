import { FormEvent, useEffect, useState } from 'react';
import { Link, Navigate, useNavigate } from 'react-router-dom';
import { authApi } from '../../api/endpoints';
import { getErrorMessage } from '../../api/client';
import { PageHeader } from '../../components/layout/PageHeader';
import { flash } from '../../components/ui/Flash';
import { Loading } from '../../components/ui/Loading';
import { useAuth } from '../../hooks/useAuth';
import './MfaPages.css';

export function MfaSetupPage() {
  const { user, refreshUser } = useAuth();
  const navigate = useNavigate();
  const [secret, setSecret] = useState('');
  const [qr, setQr] = useState('');
  const [code, setCode] = useState('');
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);

  const alreadyEnabled = Boolean(user?.totp_enabled);

  useEffect(() => {
    if (alreadyEnabled) return;
    void (async () => {
      try {
        const { data } = await authApi.mfaSetup();
        setSecret(data.secret);
        setQr(data.qr_data_url || data.qr_code || '');
      } catch (err) {
        flash.error(getErrorMessage(err, 'Impossible d’initialiser la MFA'));
      } finally {
        setLoading(false);
      }
    })();
  }, [alreadyEnabled]);

  if (alreadyEnabled && user) {
    return <Navigate to={`/users/${user.id}`} replace />;
  }

  const onSubmit = async (e: FormEvent) => {
    e.preventDefault();
    setSubmitting(true);
    try {
      await authApi.mfaEnable(code.trim());
      await refreshUser();
      flash.success('Double authentification activée.');
      navigate(user ? `/users/${user.id}` : '/dashboard', { replace: true });
    } catch (err) {
      flash.error(getErrorMessage(err, 'Code invalide. Rescannez le QR et réessayez.'));
      setCode('');
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <>
      <PageHeader
        title="Activer la double authentification"
        subtitle="Scannez le QR code avec Google Authenticator / Microsoft Authenticator."
      />
      <div className="users form">
        <div className="row">
          <div className="col" />
          <div className="col-8">
            <div className="card view-card">
              <div className="card-body">
                <p>
                  Scannez ce QR code avec <strong>Google Authenticator</strong>,{' '}
                  <strong>Microsoft Authenticator</strong> ou une app TOTP compatible.
                </p>

                <div className="text-center my-4">
                  {loading ? (
                    <Loading />
                  ) : (
                    qr && <img src={qr} alt="QR code MFA" width={220} height={220} />
                  )}
                </div>

                <p>
                  <strong>Clé secrète (saisie manuelle) :</strong>
                  <br />
                  <code className="mfa-secret">{secret}</code>
                </p>

                <p className="text-muted">
                  Ensuite, saisissez un code à 6 chiffres généré par l’application pour
                  confirmer l’activation.
                </p>

                <form onSubmit={onSubmit}>
                  <div className="input text">
                    <label htmlFor="UserCode">Code de confirmation</label>
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
                      <i className="fa-solid fa-shield-halved" /> Activer la MFA
                    </button>
                    <Link to={user ? `/users/${user.id}` : '/dashboard'} className="btn btn-link">
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
