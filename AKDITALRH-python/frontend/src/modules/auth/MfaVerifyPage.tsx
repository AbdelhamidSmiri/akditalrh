import { FormEvent, useState } from 'react';
import { Link, Navigate, useNavigate } from 'react-router-dom';
import { Alert } from '../../components/ui/Alert';
import { useAuth } from '../../hooks/useAuth';
import { getErrorMessage, tokenStorage } from '../../api/client';
import './MfaPages.css';

export function MfaVerifyPage() {
  const { completeMfa, mfaToken, logout } = useAuth();
  const navigate = useNavigate();
  const [code, setCode] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  const pending = mfaToken || tokenStorage.getMfa();
  if (!pending) {
    return <Navigate to="/login" replace />;
  }

  const onSubmit = async (e: FormEvent) => {
    e.preventDefault();
    setError('');
    setLoading(true);
    try {
      await completeMfa(code.trim());
      navigate('/dashboard');
    } catch (err) {
      setError(getErrorMessage(err, 'Code MFA invalide'));
    } finally {
      setLoading(false);
    }
  };

  const onLogout = async () => {
    tokenStorage.clear();
    tokenStorage.clearMfa();
    try {
      await logout();
    } catch {
      /* ignore */
    }
    window.location.href = '/login';
  };

  return (
    <div className="mfa-page">
      <div className="mfa-box">
        <h1>Vérification en deux étapes</h1>
        <p>Saisissez le code à 6 chiffres de votre application d’authentification.</p>
        {error && <Alert variant="danger">{error}</Alert>}
        <form onSubmit={onSubmit}>
          <input
            type="text"
            name="code"
            inputMode="numeric"
            autoComplete="one-time-code"
            maxLength={6}
            placeholder="123456"
            value={code}
            onChange={(e) => setCode(e.target.value.replace(/\D/g, '').slice(0, 6))}
            required
            aria-label="Code MFA"
          />
          <button type="submit" className="btn-mfa" disabled={loading || code.length < 6}>
            {loading ? 'Validation…' : 'Valider'}
          </button>
        </form>
        <Link to="/login" className="logout-link" onClick={(e) => { e.preventDefault(); void onLogout(); }}>
          Se déconnecter
        </Link>
      </div>
    </div>
  );
}
