import { http } from '@/plugins/axios';

export const usersApi = {
  list(params: Record<string, unknown> = {}) {
    return http.get('/users', { params });
  },
  create(payload: Record<string, unknown>) {
    return http.post('/users', payload);
  },
};
