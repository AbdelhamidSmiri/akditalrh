import axios, { AxiosError, InternalAxiosRequestConfig } from 'axios';

export const API_BASE_URL =
  import.meta.env.VITE_API_BASE_URL || 'http://localhost:5001/api/v1';

/** URL d'une image « vitrine » (hotels, chambres, appartements) — 1re image si liste séparée par virgules. */
export function publicFileUrl(folder: string, images?: string | null): string {
  const first = (images || '')
    .split(',')
    .map((s) => s.trim())
    .find(Boolean);
  if (!first) return '';
  const name = first.split('/').pop() || first;
  return `${API_BASE_URL}/files/public/${folder}/${encodeURIComponent(name)}`;
}

const ACCESS_KEY = 'access_token';
const REFRESH_KEY = 'refresh_token';
const MFA_TOKEN_KEY = 'mfa_token';

export const tokenStorage = {
  getAccess: () => localStorage.getItem(ACCESS_KEY),
  getRefresh: () => localStorage.getItem(REFRESH_KEY),
  getMfa: () => localStorage.getItem(MFA_TOKEN_KEY),
  setTokens: (access: string, refresh?: string) => {
    localStorage.setItem(ACCESS_KEY, access);
    if (refresh) localStorage.setItem(REFRESH_KEY, refresh);
  },
  setMfa: (token: string) => localStorage.setItem(MFA_TOKEN_KEY, token),
  clearMfa: () => localStorage.removeItem(MFA_TOKEN_KEY),
  clear: () => {
    localStorage.removeItem(ACCESS_KEY);
    localStorage.removeItem(REFRESH_KEY);
    localStorage.removeItem(MFA_TOKEN_KEY);
  },
};

export const api = axios.create({
  baseURL: API_BASE_URL,
  headers: { 'Content-Type': 'application/json' },
});

/** Déballe { ok, data } renvoyé par Flask. */
api.interceptors.response.use(
  (res) => {
    const body = res.data;
    if (
      body &&
      typeof body === 'object' &&
      'ok' in body &&
      (body as { ok: boolean }).ok === true &&
      'data' in body
    ) {
      res.data = (body as { data: unknown }).data;
    }
    return res;
  },
  async (error: AxiosError) => {
    const original = error.config as InternalAxiosRequestConfig & {
      _retry?: boolean;
    };
    const code = (error.response?.data as { code?: string } | undefined)?.code;
    if (
      error.response?.status === 403 &&
      code === 'mfa_enrollment_required' &&
      window.location.pathname !== '/users/mfa-setup'
    ) {
      window.location.href = '/users/mfa-setup';
      return Promise.reject(error);
    }
    if (error.response?.status === 401 && original && !original._retry) {
      const url = original.url || '';
      if (url.includes('/auth/login') || url.includes('/auth/mfa')) {
        return Promise.reject(error);
      }
      original._retry = true;
      refreshing = refreshing ?? refreshAccessToken();
      const newToken = await refreshing;
      refreshing = null;
      if (newToken) {
        original.headers.Authorization = `Bearer ${newToken}`;
        return api(original);
      }
      if (!window.location.pathname.startsWith('/login')) {
        window.location.href = '/login';
      }
    }
    return Promise.reject(error);
  },
);

api.interceptors.request.use((config: InternalAxiosRequestConfig) => {
  const token = tokenStorage.getAccess();
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

let refreshing: Promise<string | null> | null = null;

async function refreshAccessToken(): Promise<string | null> {
  const refresh = tokenStorage.getRefresh();
  if (!refresh) return null;
  try {
    const { data } = await axios.post(`${API_BASE_URL}/auth/refresh`, {
      refresh_token: refresh,
    });
    const payload =
      data && typeof data === 'object' && 'data' in data
        ? (data as { data: { access_token?: string; refresh_token?: string } }).data
        : data;
    const access = payload?.access_token as string;
    if (!access) return null;
    tokenStorage.setTokens(access, payload?.refresh_token ?? refresh);
    return access;
  } catch {
    tokenStorage.clear();
    return null;
  }
}

export function getErrorMessage(err: unknown, fallback = 'Une erreur est survenue'): string {
  if (axios.isAxiosError(err)) {
    const data = err.response?.data as { message?: string; error?: string } | undefined;
    return data?.message || data?.error || err.message || fallback;
  }
  if (err instanceof Error) return err.message;
  return fallback;
}

export function unwrapList<T>(data: unknown): T[] {
  if (Array.isArray(data)) return data as T[];
  if (data && typeof data === 'object' && 'items' in data) {
    return (data as { items: T[] }).items ?? [];
  }
  if (data && typeof data === 'object' && 'data' in data) {
    const inner = (data as { data: unknown }).data;
    if (Array.isArray(inner)) return inner as T[];
  }
  return [];
}

export default api;
