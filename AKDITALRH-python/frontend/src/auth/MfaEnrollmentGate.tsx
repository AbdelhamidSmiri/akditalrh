import { useEffect } from 'react';
import { Navigate, useLocation } from 'react-router-dom';
import { useAuthContext } from './AuthContext';
import { flash } from '../components/ui/Flash';

export const MFA_SETUP_PATH = '/users/mfa-setup';
export const MFA_ENFORCED_MESSAGE =
  'La double authentification (MFA) est obligatoire pour votre compte. Veuillez l’activer.';

/** Rôle soumis à la MFA sans MFA activée : toutes les pages renvoient vers l'activation. */
export function MfaEnrollmentGate({ children }: { children: React.ReactNode }) {
  const { user } = useAuthContext();
  const location = useLocation();
  const mustEnroll = Boolean(user?.mfa_enrollment_required);
  const blocked = mustEnroll && location.pathname !== MFA_SETUP_PATH;

  useEffect(() => {
    if (blocked) flash.error(MFA_ENFORCED_MESSAGE);
  }, [blocked, location.key]);

  if (blocked) return <Navigate to={MFA_SETUP_PATH} replace />;
  return <>{children}</>;
}
