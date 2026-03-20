import { http } from '@/plugins/axios';

export const proposalTemplatesApi = {
  list() {
    return http.get('/proposal-templates');
  },
};
