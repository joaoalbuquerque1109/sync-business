import { http } from '@/plugins/axios';

export const authApi = {
  login(payload: { login: string; password: string }) {
    return http.post('/auth/login', payload);
  },
  logout() {
    return http.post('/auth/logout');
  },
  me() {
    return http.get('/auth/me');
  },
};
