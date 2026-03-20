<script setup lang="ts">
import axios from 'axios';
import { computed, onMounted, reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { clientsApi } from '@/api/clientsApi';
import { productsApi } from '@/api/productsApi';
import { proposalTemplatesApi } from '@/api/proposalTemplatesApi';
import { proposalsApi } from '@/api/proposalsApi';
import BasePageHeader from '@/components/common/BasePageHeader.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { useAuthStore } from '@/stores/auth';

interface ClientOption {
  id: number;
  company_name: string;
  trade_name?: string | null;
}

interface ProductOption {
  id: number;
  code: string;
  name: string;
  description?: string | null;
  unit?: string | null;
  base_price: string | number;
}

interface TemplateOption {
  id: number;
  name: string;
  code: string;
}

interface ProposalItemForm {
  product_id: number | null;
  description: string;
  quantity: number;
  unit_price: number;
  discount_amount: number;
  unit: string;
}

const router = useRouter();
const auth = useAuthStore();

const loading = ref(false);
const submitting = ref(false);
const errorMessage = ref('');
const successMessage = ref('');

const clients = ref<ClientOption[]>([]);
const products = ref<ProductOption[]>([]);
const templates = ref<TemplateOption[]>([]);

const form = reactive({
  client_id: null as number | null,
  proposal_template_id: null as number | null,
  title: '',
  issue_date: new Date().toISOString().slice(0, 10),
  valid_until: new Date(Date.now() + 1000 * 60 * 60 * 24 * 15).toISOString().slice(0, 10),
  general_notes: '',
  currency: 'BRL',
  status: 'draft',
  items: [
    {
      product_id: null,
      description: '',
      quantity: 1,
      unit_price: 0,
      discount_amount: 0,
      unit: 'un',
    },
  ] as ProposalItemForm[],
});

const totals = computed(() => {
  const subtotal = form.items.reduce((sum, item) => sum + item.quantity * item.unit_price, 0);
  const discount = form.items.reduce((sum, item) => sum + item.discount_amount, 0);
  const total = Math.max(subtotal - discount, 0);

  return {
    subtotal,
    discount,
    total,
  };
});

function extractCollection(payload: unknown) {
  if (Array.isArray(payload)) {
    return payload;
  }

  if (payload && typeof payload === 'object' && 'data' in payload && Array.isArray((payload as { data: unknown }).data)) {
    return (payload as { data: unknown[] }).data;
  }

  return [];
}

function formatCurrency(value: number) {
  return new Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: 'BRL',
  }).format(value);
}

function itemTotal(item: ProposalItemForm) {
  return Math.max(item.quantity * item.unit_price - item.discount_amount, 0);
}

function addItem() {
  form.items.push({
    product_id: null,
    description: '',
    quantity: 1,
    unit_price: 0,
    discount_amount: 0,
    unit: 'un',
  });
}

function removeItem(index: number) {
  if (form.items.length === 1) {
    return;
  }

  form.items.splice(index, 1);
}

function applyProduct(index: number) {
  const item = form.items[index];
  const product = products.value.find((entry) => entry.id === item.product_id);

  if (!product) {
    return;
  }

  item.description = product.description || product.name;
  item.unit = product.unit || 'un';
  item.unit_price = Number(product.base_price || 0);
}

async function loadOptions() {
  loading.value = true;
  errorMessage.value = '';

  try {
    const [clientsResponse, productsResponse, templatesResponse] = await Promise.all([
      clientsApi.list({ per_page: 100 }),
      productsApi.list({ per_page: 100 }),
      proposalTemplatesApi.list(),
    ]);

    clients.value = extractCollection(clientsResponse.data.data) as ClientOption[];
    products.value = extractCollection(productsResponse.data.data) as ProductOption[];
    templates.value = extractCollection(templatesResponse.data.data) as TemplateOption[];
  } catch {
    errorMessage.value = 'Nao foi possivel carregar os dados da proposta.';
  } finally {
    loading.value = false;
  }
}

async function submit() {
  submitting.value = true;
  errorMessage.value = '';
  successMessage.value = '';

  try {
    const payload = {
      client_id: form.client_id,
      proposal_template_id: form.proposal_template_id,
      responsible_user_id: auth.user?.id,
      title: form.title || null,
      issue_date: form.issue_date,
      valid_until: form.valid_until || null,
      general_notes: form.general_notes || null,
      currency: form.currency,
      status: form.status,
      items: form.items.map((item) => ({
        product_id: item.product_id,
        description: item.description,
        quantity: Number(item.quantity),
        unit_price: Number(item.unit_price),
        discount_amount: Number(item.discount_amount),
        unit: item.unit,
      })),
    };

    const response = await proposalsApi.create(payload);
    const proposal = response.data.data;

    successMessage.value = `Proposta ${proposal.number} criada com sucesso.`;
    await router.push('/proposals');
  } catch (error) {
    if (axios.isAxiosError(error)) {
      errorMessage.value = error.response?.data?.message || 'Nao foi possivel salvar a proposta.';
    } else {
      errorMessage.value = 'Nao foi possivel salvar a proposta.';
    }
  } finally {
    submitting.value = false;
  }
}

onMounted(() => {
  loadOptions();
});
</script>

<template>
  <AppLayout>
    <BasePageHeader title="Nova proposta" subtitle="Monte a proposta comercial e envie para o backend." action-label="Ver propostas" action-to="/proposals" />

    <v-alert v-if="errorMessage" type="error" variant="tonal" class="mb-4">{{ errorMessage }}</v-alert>
    <v-alert v-if="successMessage" type="success" variant="tonal" class="mb-4">{{ successMessage }}</v-alert>

    <v-row>
      <v-col cols="12" lg="8">
        <v-card rounded="xl" class="mb-4">
          <v-card-text class="d-flex flex-column ga-4">
            <v-select
              v-model="form.client_id"
              label="Cliente"
              variant="outlined"
              :items="clients"
              item-title="company_name"
              item-value="id"
              :loading="loading"
            />
            <v-select
              v-model="form.proposal_template_id"
              label="Template"
              variant="outlined"
              :items="templates"
              item-title="name"
              item-value="id"
              :loading="loading"
              clearable
            />
            <v-text-field v-model="form.title" label="Titulo da proposta" variant="outlined" />
            <v-row>
              <v-col cols="12" md="6">
                <v-text-field v-model="form.issue_date" type="date" label="Data de emissao" variant="outlined" />
              </v-col>
              <v-col cols="12" md="6">
                <v-text-field v-model="form.valid_until" type="date" label="Validade" variant="outlined" />
              </v-col>
            </v-row>
            <v-textarea v-model="form.general_notes" label="Observacoes gerais" variant="outlined" rows="4" />
          </v-card-text>
        </v-card>

        <v-card rounded="xl">
          <v-card-title class="d-flex align-center justify-space-between">
            <span>Itens da proposta</span>
            <v-btn color="primary" variant="tonal" prepend-icon="mdi-plus" @click="addItem">Adicionar item</v-btn>
          </v-card-title>
          <v-card-text class="d-flex flex-column ga-4">
            <v-card v-for="(item, index) in form.items" :key="index" rounded="lg" variant="outlined">
              <v-card-text>
                <v-row>
                  <v-col cols="12" md="5">
                    <v-select
                      v-model="item.product_id"
                      label="Produto"
                      variant="outlined"
                      :items="products"
                      item-title="name"
                      item-value="id"
                      :loading="loading"
                      clearable
                      @update:model-value="applyProduct(index)"
                    />
                  </v-col>
                  <v-col cols="12" md="7">
                    <v-text-field v-model="item.description" label="Descricao" variant="outlined" />
                  </v-col>
                  <v-col cols="12" md="3">
                    <v-text-field v-model.number="item.quantity" type="number" min="1" label="Quantidade" variant="outlined" />
                  </v-col>
                  <v-col cols="12" md="3">
                    <v-text-field v-model.number="item.unit_price" type="number" min="0" step="0.01" label="Preco unitario" variant="outlined" />
                  </v-col>
                  <v-col cols="12" md="3">
                    <v-text-field v-model.number="item.discount_amount" type="number" min="0" step="0.01" label="Desconto" variant="outlined" />
                  </v-col>
                  <v-col cols="12" md="2">
                    <v-text-field v-model="item.unit" label="Unidade" variant="outlined" />
                  </v-col>
                  <v-col cols="12" md="1" class="d-flex align-center justify-end">
                    <v-btn icon="mdi-delete-outline" color="error" variant="text" @click="removeItem(index)" />
                  </v-col>
                </v-row>
                <div class="text-right text-body-2 font-weight-medium">Total do item: {{ formatCurrency(itemTotal(item)) }}</div>
              </v-card-text>
            </v-card>
          </v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" lg="4">
        <v-card rounded="xl" class="mb-4">
          <v-card-title>Resumo</v-card-title>
          <v-card-text>
            <div class="d-flex justify-space-between mb-2"><span>Subtotal</span><strong>{{ formatCurrency(totals.subtotal) }}</strong></div>
            <div class="d-flex justify-space-between mb-2"><span>Desconto</span><strong>{{ formatCurrency(totals.discount) }}</strong></div>
            <div class="d-flex justify-space-between text-h6"><span>Total</span><strong>{{ formatCurrency(totals.total) }}</strong></div>
          </v-card-text>
        </v-card>

        <v-card rounded="xl">
          <v-card-title>Acoes</v-card-title>
          <v-card-text class="d-flex flex-column ga-3">
            <v-btn color="primary" size="large" block :loading="submitting" @click="submit">Salvar proposta</v-btn>
            <v-btn variant="outlined" size="large" block @click="router.push('/proposals')">Cancelar</v-btn>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </AppLayout>
</template>
