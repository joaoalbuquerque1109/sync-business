<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import { productsApi } from '@/api/productsApi';
import BasePageHeader from '@/components/common/BasePageHeader.vue';
import AppLayout from '@/layouts/AppLayout.vue';

interface ProductRow {
  id: number;
  code: string;
  name: string;
  category?: string | null;
  base_price: string | number;
  status: string;
}

const loading = ref(false);
const errorMessage = ref('');
const rows = ref<ProductRow[]>([]);

const hasRows = computed(() => rows.value.length > 0);

function extractCollection(payload: unknown) {
  if (Array.isArray(payload)) return payload;
  if (payload && typeof payload === 'object' && 'data' in payload && Array.isArray((payload as { data: unknown }).data)) {
    return (payload as { data: unknown[] }).data;
  }
  return [];
}

function formatCurrency(value: string | number) {
  return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(Number(value || 0));
}

async function loadProducts() {
  loading.value = true;
  errorMessage.value = '';
  try {
    const response = await productsApi.list({ per_page: 50 });
    rows.value = extractCollection(response.data.data) as ProductRow[];
  } catch {
    errorMessage.value = 'Nao foi possivel carregar os produtos.';
  } finally {
    loading.value = false;
  }
}

onMounted(loadProducts);
</script>

<template>
  <AppLayout>
    <BasePageHeader title="Produtos" subtitle="Catalogo comercial e tecnico" action-label="Novo produto" action-to="/products/new" />
    <v-card rounded="xl">
      <v-card-text>
        <v-alert v-if="errorMessage" type="error" variant="tonal" class="mb-4">{{ errorMessage }}</v-alert>
        <v-progress-linear v-if="loading" indeterminate color="primary" class="mb-4" />
        <v-table v-if="hasRows">
          <thead>
            <tr><th>Codigo</th><th>Nome</th><th>Categoria</th><th>Preco base</th><th>Status</th></tr>
          </thead>
          <tbody>
            <tr v-for="product in rows" :key="product.id">
              <td>{{ product.code }}</td>
              <td>{{ product.name }}</td>
              <td>{{ product.category || '-' }}</td>
              <td>{{ formatCurrency(product.base_price) }}</td>
              <td>{{ product.status }}</td>
            </tr>
          </tbody>
        </v-table>
        <div v-else class="text-medium-emphasis py-8 text-center">Nenhum produto encontrado.</div>
      </v-card-text>
    </v-card>
  </AppLayout>
</template>
