import { Navigate } from 'react-router-dom';
import { useAuthContext } from './AuthContext';

interface Props {
  roles: string[];
  children: React.ReactNode;
  fallback?: React.ReactNode;
}

export function RequireRole({ roles, children, fallback }: Props) {
  const { hasRole, isAdmin, loading } = useAuthContext();

  if (loading) return null;

  if (isAdmin || hasRole(...roles)) {
    return <>{children}</>;
  }

  if (fallback) return <>{fallback}</>;

  return <Navigate to="/dashboard" replace />;
}
