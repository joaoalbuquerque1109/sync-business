import { http } from '@/plugins/axios';

export const proposalsApi = {
  list(params: Record<string, unknown> = {}) {
    return http.get('/proposals', { params });
  },
  create(payload: Record<string, unknown>) {
    return http.post('/proposals', payload);
  },
};
