<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import { clientsApi } from '@/api/clientsApi';
import BasePageHeader from '@/components/common/BasePageHeader.vue';
import AppLayout from '@/layouts/AppLayout.vue';

interface ClientRow {
  id: number;
  company_name: string;
  trade_name?: string | null;
  cnpj?: string | null;
  state?: string | null;
  is_active: boolean;
}

const search = ref('');
const loading = ref(false);
const errorMessage = ref('');
const rows = ref<ClientRow[]>([]);

const hasRows = computed(() => rows.value.length > 0);

function extractCollection(payload: unknown) {
  if (Array.isArray(payload)) return payload;
  if (payload && typeof payload === 'object' && 'data' in payload && Array.isArray((payload as { data: unknown }).data)) {
    return (payload as { data: unknown[] }).data;
  }
  return [];
}

async function loadClients() {
  loading.value = true;
  errorMessage.value = '';
  try {
    const response = await clientsApi.list({ per_page: 50, search: search.value || undefined });
    rows.value = extractCollection(response.data.data) as ClientRow[];
  } catch {
    errorMessage.value = 'Nao foi possivel carregar os clientes.';
  } finally {
    loading.value = false;
  }
}

onMounted(loadClients);
</script>

<template>
  <AppLayout>
    <BasePageHeader title="Clientes" subtitle="Listagem com filtros e busca" action-label="Novo cliente" action-to="/clients/new" />
    <v-card rounded="xl">
      <v-card-text>
        <v-alert v-if="errorMessage" type="error" variant="tonal" class="mb-4">{{ errorMessage }}</v-alert>
        <div class="d-flex ga-3 mb-4">
          <v-text-field v-model="search" label="Buscar por nome ou CNPJ" prepend-inner-icon="mdi-magnify" variant="outlined" hide-details />
          <v-btn color="primary" @click="loadClients">Buscar</v-btn>
        </div>
        <v-progress-linear v-if="loading" indeterminate color="primary" class="mb-4" />
        <v-table v-if="hasRows">
          <thead>
            <tr><th>Razao social</th><th>Fantasia</th><th>CNPJ</th><th>UF</th><th>Status</th></tr>
          </thead>
          <tbody>
            <tr v-for="client in rows" :key="client.id">
              <td>{{ client.company_name }}</td>
              <td>{{ client.trade_name || '-' }}</td>
              <td>{{ client.cnpj || '-' }}</td>
              <td>{{ client.state || '-' }}</td>
              <td>{{ client.is_active ? 'Ativo' : 'Inativo' }}</td>
            </tr>
          </tbody>
        </v-table>
        <div v-else class="text-medium-emphasis py-8 text-center">Nenhum cliente encontrado.</div>
      </v-card-text>
    </v-card>
  </AppLayout>
</template>
