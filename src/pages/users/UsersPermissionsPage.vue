<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import { rolesApi } from '@/api/rolesApi';
import { usersApi } from '@/api/usersApi';
import BasePageHeader from '@/components/common/BasePageHeader.vue';
import AppLayout from '@/layouts/AppLayout.vue';

interface UserRow {
  id: number;
  name: string;
  username?: string | null;
  email: string;
  status: string;
  roles?: Array<{ id: number; name: string }>;
}

const loading = ref(false);
const errorMessage = ref('');
const users = ref<UserRow[]>([]);
const roles = ref<Array<{ id: number; name: string; permissions?: unknown[] }>>([]);

const hasUsers = computed(() => users.value.length > 0);

function extractCollection(payload: unknown) {
  if (Array.isArray(payload)) return payload;
  if (payload && typeof payload === 'object' && 'data' in payload && Array.isArray((payload as { data: unknown }).data)) {
    return (payload as { data: unknown[] }).data;
  }
  return [];
}

async function loadData() {
  loading.value = true;
  errorMessage.value = '';
  try {
    const [usersResponse, rolesResponse] = await Promise.all([usersApi.list({ per_page: 50 }), rolesApi.list()]);
    users.value = extractCollection(usersResponse.data.data) as UserRow[];
    roles.value = extractCollection(rolesResponse.data.data) as Array<{ id: number; name: string; permissions?: unknown[] }>;
  } catch {
    errorMessage.value = 'Nao foi possivel carregar usuarios e papeis.';
  } finally {
    loading.value = false;
  }
}

onMounted(loadData);
</script>

<template>
  <AppLayout>
    <BasePageHeader title="Usuarios e permissoes" subtitle="RBAC inicial com papeis e acoes por modulo" action-label="Novo usuario" action-to="/users/new" />
    <v-alert v-if="errorMessage" type="error" variant="tonal" class="mb-4">{{ errorMessage }}</v-alert>
    <v-card rounded="xl">
      <v-card-text>
        <v-progress-linear v-if="loading" indeterminate color="primary" class="mb-4" />
        <v-tabs>
          <v-tab>Usuarios</v-tab>
          <v-tab>Papeis</v-tab>
        </v-tabs>
        <v-window class="mt-4">
          <v-window-item>
            <v-table v-if="hasUsers">
              <thead>
                <tr><th>Nome</th><th>Usuario</th><th>E-mail</th><th>Status</th><th>Papeis</th></tr>
              </thead>
              <tbody>
                <tr v-for="user in users" :key="user.id">
                  <td>{{ user.name }}</td>
                  <td>{{ user.username || '-' }}</td>
                  <td>{{ user.email }}</td>
                  <td>{{ user.status }}</td>
                  <td>{{ user.roles?.map((role) => role.name).join(', ') || '-' }}</td>
                </tr>
              </tbody>
            </v-table>
            <div v-else class="text-medium-emphasis py-8 text-center">Nenhum usuario encontrado.</div>
          </v-window-item>
          <v-window-item>
            <v-list>
              <v-list-item v-for="role in roles" :key="role.id" :title="role.name" :subtitle="`${role.permissions?.length || 0} permissoes`" />
            </v-list>
          </v-window-item>
        </v-window>
      </v-card-text>
    </v-card>
  </AppLayout>
</template>
