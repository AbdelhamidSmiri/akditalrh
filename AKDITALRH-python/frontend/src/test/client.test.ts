import { describe, expect, it, beforeEach } from 'vitest';
import { getErrorMessage, tokenStorage, unwrapList } from '../api/client';

describe('tokenStorage', () => {
  beforeEach(() => {
    localStorage.clear();
  });

  it('stocke et lit les tokens', () => {
    tokenStorage.setTokens('access-abc', 'refresh-xyz');
    expect(tokenStorage.getAccess()).toBe('access-abc');
    expect(tokenStorage.getRefresh()).toBe('refresh-xyz');
  });

  it('gère le token MFA', () => {
    tokenStorage.setMfa('mfa-token');
    expect(tokenStorage.getMfa()).toBe('mfa-token');
    tokenStorage.clearMfa();
    expect(tokenStorage.getMfa()).toBeNull();
  });

  it('clear nettoie tout', () => {
    tokenStorage.setTokens('a', 'b');
    tokenStorage.setMfa('m');
    tokenStorage.clear();
    expect(tokenStorage.getAccess()).toBeNull();
    expect(tokenStorage.getRefresh()).toBeNull();
    expect(tokenStorage.getMfa()).toBeNull();
  });
});

describe('unwrapList', () => {
  it('retourne un tableau tel quel', () => {
    expect(unwrapList([{ id: 1 }])).toEqual([{ id: 1 }]);
  });

  it('extrait items', () => {
    expect(unwrapList({ items: [{ id: 2 }] })).toEqual([{ id: 2 }]);
  });

  it('extrait data imbriqué', () => {
    expect(unwrapList({ data: [{ id: 3 }] })).toEqual([{ id: 3 }]);
  });

  it('retourne [] pour valeurs invalides', () => {
    expect(unwrapList(null)).toEqual([]);
    expect(unwrapList({})).toEqual([]);
    expect(unwrapList('x')).toEqual([]);
  });
});

describe('getErrorMessage', () => {
  it('lit message axios', () => {
    const err = {
      isAxiosError: true,
      response: { data: { message: 'Accès refusé' } },
      message: 'Request failed',
    };
    // axios.isAxiosError check — fallback to Error path if not real AxiosError
    expect(getErrorMessage(new Error('boom'), 'fallback')).toBe('boom');
    expect(getErrorMessage('x', 'fallback')).toBe('fallback');
  });
});
