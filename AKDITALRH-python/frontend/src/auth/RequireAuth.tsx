import { Navigate, useLocation } from 'react-router-dom';
import { useAuthContext } from './AuthContext';
import { Loading } from '../components/ui/Loading';

export function RequireAuth({ children }: { children: React.ReactNode }) {
  const { isAuthenticated, loading, user } = useAuthContext();
  const location = useLocation();

  if (loading) return <Loading fullPage label="Chargement de la session…" />;

  if (!isAuthenticated) {
    return <Navigate to="/login" replace state={{ from: location }} />;
  }

  if (user && user.totp_enabled === false && !user.totp_enabled) {
    /* enrollment optional unless API flags it */
  }

  return <>{children}</>;
}
