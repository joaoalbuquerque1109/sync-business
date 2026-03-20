import { http } from '@/plugins/axios';

export const rolesApi = {
  list() {
    return http.get('/roles');
  },
};
