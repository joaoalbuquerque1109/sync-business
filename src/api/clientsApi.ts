import { http } from '@/plugins/axios';

export const clientsApi = {
  list(params: Record<string, unknown> = {}) {
    return http.get('/clients', { params });
  },
  create(payload: Record<string, unknown>) {
    return http.post('/clients', payload);
  },
};
