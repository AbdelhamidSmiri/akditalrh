import { describe, expect, it } from 'vitest';
import { render, screen } from '@testing-library/react';
import { MemoryRouter, Routes, Route } from 'react-router-dom';
import { AuthContext } from '../auth/AuthContext';
import { RequireAuth } from '../auth/RequireAuth';
import { RequireRole } from '../auth/RequireRole';
import type { AuthUser } from '../types';

const adminUser: AuthUser = {
  id: 1,
  username: 'admin@test.local',
  role_id: 1,
  site_id: 3,
  role: { id: 1, role: 'Admin' },
  role_name: 'Admin',
  etat: 'Actif',
  totp_enabled: false,
};

function makeAuth(overrides: Record<string, unknown> = {}) {
  return {
    user: null,
    loading: false,
    mfaToken: null,
    isAuthenticated: false,
    login: async () => ({}),
    completeMfa: async () => undefined,
    logout: async () => undefined,
    setSession: () => undefined,
    refreshUser: async () => undefined,
    hasRole: (...roles: string[]) => {
      const name = (overrides.user as AuthUser | null)?.role_name || '';
      return roles.some((r) => r.toLowerCase() === name.toLowerCase());
    },
    isAdmin: false,
    isAgence: false,
    ...overrides,
  };
}

function wrap(ui: React.ReactNode, authOverrides: Record<string, unknown> = {}) {
  const value = makeAuth(authOverrides);
  return (
    <AuthContext.Provider value={value as never}>
      <MemoryRouter initialEntries={['/secure']}>
        <Routes>
          <Route path="/login" element={<div>Login page</div>} />
          <Route path="/dashboard" element={<div>Dashboard</div>} />
          <Route path="/secure" element={ui} />
        </Routes>
      </MemoryRouter>
    </AuthContext.Provider>
  );
}

describe('RequireAuth', () => {
  it('redirige vers login si non authentifié', () => {
    render(wrap(<RequireAuth><div>Privé</div></RequireAuth>));
    expect(screen.getByText('Login page')).toBeInTheDocument();
  });

  it('affiche le contenu si authentifié', () => {
    render(
      wrap(<RequireAuth><div>Privé</div></RequireAuth>, {
        isAuthenticated: true,
        user: adminUser,
      }),
    );
    expect(screen.getByText('Privé')).toBeInTheDocument();
  });
});

describe('RequireRole', () => {
  it('autorise Admin', () => {
    render(
      wrap(
        <RequireRole roles={['Admin']}>
          <div>Admin zone</div>
        </RequireRole>,
        {
          isAuthenticated: true,
          user: adminUser,
          isAdmin: true,
          hasRole: () => true,
        },
      ),
    );
    expect(screen.getByText('Admin zone')).toBeInTheDocument();
  });

  it('redirige si rôle insuffisant', () => {
    render(
      wrap(
        <RequireRole roles={['Admin']}>
          <div>Admin zone</div>
        </RequireRole>,
        {
          isAuthenticated: true,
          user: { ...adminUser, role_name: 'Agence', role: { id: 2, role: 'Agence' } },
          isAdmin: false,
          hasRole: () => false,
        },
      ),
    );
    expect(screen.getByText('Dashboard')).toBeInTheDocument();
  });
});
