import { defineStore } from 'pinia';

export interface AuthRole {
  id: number;
  name: string;
  slug: string;
}

export interface AuthUser {
  id: number;
  name: string;
  username: string | null;
  email: string;
  status: string;
  roles?: AuthRole[];
}

interface AuthSession {
  token: string;
  user: AuthUser;
}

export const AUTH_STORAGE_KEY = 'sync-business.auth';

function readStoredSession(): AuthSession | null {
  if (typeof window === 'undefined') {
    return null;
  }

  const raw = window.localStorage.getItem(AUTH_STORAGE_KEY);

  if (!raw) {
    return null;
  }

  try {
    return JSON.parse(raw) as AuthSession;
  } catch {
    window.localStorage.removeItem(AUTH_STORAGE_KEY);

    return null;
  }
}

function persistSession(session: AuthSession | null) {
  if (typeof window === 'undefined') {
    return;
  }

  if (!session) {
    window.localStorage.removeItem(AUTH_STORAGE_KEY);

    return;
  }

  window.localStorage.setItem(AUTH_STORAGE_KEY, JSON.stringify(session));
}

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: '',
    user: null as AuthUser | null,
    initialized: false,
  }),
  getters: {
    isAuthenticated: (state) => Boolean(state.token),
    displayName: (state) => state.user?.name || state.user?.username || 'Usuario',
  },
  actions: {
    initialize() {
      if (this.initialized) {
        return;
      }

      const session = readStoredSession();

      if (session) {
        this.token = session.token;
        this.user = session.user;
      }

      this.initialized = true;
    },
    setSession(payload: AuthSession) {
      this.token = payload.token;
      this.user = payload.user;
      this.initialized = true;
      persistSession(payload);
    },
    clearSession() {
      this.token = '';
      this.user = null;
      this.initialized = true;
      persistSession(null);
    },
  },
});
