<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import { proposalsApi } from '@/api/proposalsApi';
import BasePageHeader from '@/components/common/BasePageHeader.vue';
import AppLayout from '@/layouts/AppLayout.vue';

interface ProposalRow {
  id: number;
  number: string;
  status: string;
  valid_until: string | null;
  total_amount: string | number;
  client?: {
    company_name?: string;
  };
}

const loading = ref(false);
const errorMessage = ref('');
const proposals = ref<ProposalRow[]>([]);

const hasRows = computed(() => proposals.value.length > 0);

function extractCollection(payload: unknown) {
  if (Array.isArray(payload)) {
    return payload;
  }

  if (payload && typeof payload === 'object' && 'data' in payload && Array.isArray((payload as { data: unknown }).data)) {
    return (payload as { data: unknown[] }).data;
  }

  return [];
}

function formatDate(value: string | null) {
  if (!value) {
    return '-';
  }

  return new Intl.DateTimeFormat('pt-BR').format(new Date(value));
}

function formatCurrency(value: string | number) {
  return new Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: 'BRL',
  }).format(Number(value || 0));
}

async function loadProposals() {
  loading.value = true;
  errorMessage.value = '';

  try {
    const response = await proposalsApi.list({ per_page: 50 });
    proposals.value = extractCollection(response.data.data) as ProposalRow[];
  } catch {
    errorMessage.value = 'Nao foi possivel carregar as propostas.';
  } finally {
    loading.value = false;
  }
}

onMounted(() => {
  loadProposals();
});
</script>

<template>
  <AppLayout>
    <BasePageHeader title="Propostas" subtitle="Pipeline comercial e exportacoes" action-label="Nova proposta" action-to="/proposals/new" />

    <v-alert v-if="errorMessage" type="error" variant="tonal" class="mb-4">{{ errorMessage }}</v-alert>

    <v-card rounded="xl">
      <v-card-text>
        <v-progress-linear v-if="loading" indeterminate color="primary" class="mb-4" />

        <v-table v-if="hasRows">
          <thead>
            <tr>
              <th>Numero</th>
              <th>Cliente</th>
              <th>Status</th>
              <th>Validade</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="proposal in proposals" :key="proposal.id">
              <td>{{ proposal.number }}</td>
              <td>{{ proposal.client?.company_name || '-' }}</td>
              <td>{{ proposal.status }}</td>
              <td>{{ formatDate(proposal.valid_until) }}</td>
              <td>{{ formatCurrency(proposal.total_amount) }}</td>
            </tr>
          </tbody>
        </v-table>

        <div v-else class="text-medium-emphasis py-8 text-center">Nenhuma proposta cadastrada ainda.</div>
      </v-card-text>
    </v-card>
  </AppLayout>
</template>
