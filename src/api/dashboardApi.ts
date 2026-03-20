import { http } from '@/plugins/axios';

export const dashboardApi = {
  summary() {
    return http.get('/dashboard/summary');
  },
};
