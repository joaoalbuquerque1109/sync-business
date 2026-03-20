import { defineStore } from 'pinia';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: '',
    user: null as null | { name: string; email: string },
  }),
  getters: {
    isAuthenticated: (state) => Boolean(state.token),
  },
  actions: {
    setSession(payload: { token: string; user: { name: string; email: string } }) {
      this.token = payload.token;
      this.user = payload.user;
    },
    clearSession() {
      this.token = '';
      this.user = null;
    },
  },
});
