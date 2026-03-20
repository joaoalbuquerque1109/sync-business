import { http } from '@/plugins/axios';

export const productsApi = {
  list(params: Record<string, unknown> = {}) {
    return http.get('/products', { params });
  },
};
