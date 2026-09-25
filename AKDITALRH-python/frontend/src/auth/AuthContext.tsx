import {
  createContext,
  useCallback,
  useContext,
  useEffect,
  useMemo,
  useState,
  type ReactNode,
} from 'react';
import { authApi } from '../api/endpoints';
import { getErrorMessage, tokenStorage } from '../api/client';
import type { AuthUser, LoginResponse } from '../types';

interface AuthState {
  user: AuthUser | null;
  loading: boolean;
  mfaToken: string | null;
  isAuthenticated: boolean;
}

interface AuthContextValue extends AuthState {
  login: (username: string, password: string) => Promise<LoginResponse>;
  completeMfa: (code: string) => Promise<void>;
  logout: () => Promise<void>;
  setSession: (data: LoginResponse) => void;
  refreshUser: () => Promise<void>;
  hasRole: (...roles: string[]) => boolean;
  isAdmin: boolean;
  isAgence: boolean;
}

export const AuthContext = createContext<AuthContextValue | null>(null);

function applySession(data: LoginResponse): AuthUser | null {
  if (data.access_token) {
    tokenStorage.setTokens(data.access_token, data.refresh_token);
    tokenStorage.clearMfa();
  }
  return data.user ?? null;
}

export function AuthProvider({ children }: { children: ReactNode }) {
  const [user, setUser] = useState<AuthUser | null>(null);
  const [loading, setLoading] = useState(true);
  const [mfaToken, setMfaToken] = useState<string | null>(
    () => tokenStorage.getMfa(),
  );

  const refreshUser = useCallback(async () => {
    const token = tokenStorage.getAccess();
    if (!token) {
      setUser(null);
      setLoading(false);
      return;
    }
    try {
      const { data } = await authApi.me();
      setUser({
        ...data,
        role_name: data.role?.role ?? (data as AuthUser).role_name,
      });
    } catch {
      tokenStorage.clear();
      setUser(null);
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    void refreshUser();
  }, [refreshUser]);

  const setSession = useCallback((data: LoginResponse) => {
    const u = applySession(data);
    if (u) setUser(u);
    setMfaToken(null);
  }, []);

  const login = useCallback(async (username: string, password: string) => {
    const { data: raw } = await authApi.login(username, password);
    const data = {
      ...raw,
      mfa_setup_required:
        raw.mfa_setup_required ??
        (raw as { setup_required?: boolean }).setup_required,
    } as LoginResponse;
    if (data.mfa_required && data.mfa_token) {
      tokenStorage.setMfa(data.mfa_token);
      setMfaToken(data.mfa_token);
      return data;
    }
    if (data.access_token) {
      const u = applySession(data);
      setUser(u);
      setMfaToken(null);
    }
    return data;
  }, []);

  const completeMfa = useCallback(
    async (code: string) => {
      const token = mfaToken || tokenStorage.getMfa();
      if (!token) throw new Error('Session MFA expirée');
      const { data } = await authApi.mfaVerify(token, code);
      const u = applySession(data);
      if (!u && data.access_token) {
        await refreshUser();
      } else {
        setUser(u);
      }
      setMfaToken(null);
    },
    [mfaToken, refreshUser],
  );

  const logout = useCallback(async () => {
    try {
      await authApi.logout();
    } catch {
      /* ignore */
    }
    tokenStorage.clear();
    setUser(null);
    setMfaToken(null);
  }, []);

  const roleName = user?.role_name || user?.role?.role || '';

  const hasRole = useCallback(
    (...roles: string[]) => {
      if (!roleName) return false;
      return roles.some((r) => r.toLowerCase() === roleName.toLowerCase());
    },
    [roleName],
  );

  const value = useMemo<AuthContextValue>(
    () => ({
      user,
      loading,
      mfaToken,
      isAuthenticated: Boolean(user && tokenStorage.getAccess()),
      login,
      completeMfa,
      logout,
      setSession,
      refreshUser,
      hasRole,
      isAdmin: hasRole('Admin'),
      isAgence: hasRole('Agence'),
    }),
    [
      user,
      loading,
      mfaToken,
      login,
      completeMfa,
      logout,
      setSession,
      refreshUser,
      hasRole,
    ],
  );

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
}

export function useAuthContext(): AuthContextValue {
  const ctx = useContext(AuthContext);
  if (!ctx) throw new Error('useAuthContext must be used within AuthProvider');
  return ctx;
}

export { getErrorMessage };
