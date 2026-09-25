import { Navigate, Outlet } from 'react-router-dom';
import { useAuthContext } from './AuthContext';
import { tokenStorage } from '../api/client';

/** Redirige vers MFA verify si un challenge est en cours. */
export function MfaGate({ children }: { children?: React.ReactNode }) {
  const { mfaToken, isAuthenticated } = useAuthContext();
  const pending = mfaToken || tokenStorage.getMfa();

  if (pending && !isAuthenticated) {
    return <Navigate to="/mfa/verify" replace />;
  }

  return children ? <>{children}</> : <Outlet />;
}
