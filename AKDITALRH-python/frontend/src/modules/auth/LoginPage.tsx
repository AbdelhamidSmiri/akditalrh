import { FormEvent, useState } from 'react';
import { Link, Navigate, useNavigate } from 'react-router-dom';
import { isAxiosError } from 'axios';
import { useAuth } from '../../hooks/useAuth';
import { getErrorMessage } from '../../api/client';
import { flash } from '../../components/ui/Flash';

const INVALID_CREDENTIALS = 'E-mail ou mot de passe incorrect. Veuillez réessayer.';

export function LoginPage() {
  const { login, isAuthenticated, isAgence } = useAuth();
  const navigate = useNavigate();
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(false);

  if (isAuthenticated) {
    return <Navigate to={isAgence ? '/agence/vols' : '/dashboard'} replace />;
  }

  const onSubmit = async (e: FormEvent) => {
    e.preventDefault();
    setLoading(true);
    try {
      const data = await login(username.trim(), password);
      if (data.mfa_required && !data.access_token) {
        navigate('/mfa/verify');
        return;
      }
      if (!data.user?.mfa_enrollment_required) {
        flash.success(`Bonjour ${data.user?.nom ?? ''}, vous êtes connecté avec succès.`);
      }
      const role = data.user?.role_name || data.user?.role?.role;
      navigate(role === 'Agence' ? '/agence/vols' : '/dashboard');
    } catch (err) {
      const status = isAxiosError(err) ? err.response?.status : undefined;
      if (status === 401) flash.error(INVALID_CREDENTIALS);
      else if (status === 429) flash.error('Trop de tentatives. Réessayez dans environ 1 minute(s).');
      else if (!status || status >= 500) flash.error('Le serveur est momentanément indisponible. Veuillez réessayer.');
      else flash.error(getErrorMessage(err, INVALID_CREDENTIALS));
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="container-fluid login-page p-0 m-0">
      <div className="row m-0 p-0 h-100">
        <div className="col-md-6 col-sm-12 p-0 m-0 d-none d-md-block">
          <div className="image-bg">
            <div className="text-box">
              <h1 className="text-white poppins-bold fs-1 mb-5">
                Bienvenue sur la plateforme de gestion des moyens généraux
                Akdital
              </h1>
            </div>
          </div>
        </div>
        <div className="col-md-6 col-sm-12 p-0 m-0">
          <div className="login-form-panel">
            <div className="mb-5">
              <img src="/img/svg/logo_login.svg" alt="Akdital" width={281} height={73} />
            </div>
            <h2 className="poppins-bold fs-1 mb-4 text-color-primary">
              Connexion à votre espace
            </h2>
            <p className="login-slogan">
              Veuillez saisir vos identifiants pour accéder à l’interface de
              gestion complète.
            </p>
            <form onSubmit={onSubmit} className="w-100">
              <div className="text">
                <label htmlFor="username">E-mail ou nom d&apos;utilisateur</label>
                <input
                  id="username"
                  type="text"
                  name="username"
                  autoComplete="username"
                  placeholder=" "
                  value={username}
                  onChange={(e) => setUsername(e.target.value)}
                  required
                />
              </div>
              <div className="password">
                <label htmlFor="password">Mot de passe</label>
                <input
                  id="password"
                  type="password"
                  name="password"
                  autoComplete="current-password"
                  placeholder=" "
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  required
                />
              </div>
              <div className="links">
                <label className="control control--checkbox">
                  Se souvenir de moi
                  <input type="checkbox" defaultChecked />
                  <div className="control__indicator" />
                </label>
                <Link to="/forgot-password">Mot de passe oublié ?</Link>
              </div>
              <button type="submit" className="btn-login" disabled={loading}>
                {loading ? 'Connexion…' : 'Se connecter'}
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  );
}
