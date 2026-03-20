<script setup lang="ts">
import axios from 'axios';
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { productsApi } from '@/api/productsApi';
import BasePageHeader from '@/components/common/BasePageHeader.vue';
import AppLayout from '@/layouts/AppLayout.vue';

const router = useRouter();
const submitting = ref(false);
const errorMessage = ref('');

const form = reactive({
  code: '',
  name: '',
  category: '',
  unit: 'un',
  base_price: 0,
  status: 'active',
  description: '',
  technical_notes: '',
});

async function submit() {
  submitting.value = true;
  errorMessage.value = '';

  try {
    await productsApi.create(form);
    await router.push('/products');
  } catch (error) {
    if (axios.isAxiosError(error)) {
      errorMessage.value = error.response?.data?.message || 'Nao foi possivel salvar o produto.';
    } else {
      errorMessage.value = 'Nao foi possivel salvar o produto.';
    }
  } finally {
    submitting.value = false;
  }
}
</script>

<template>
  <AppLayout>
    <BasePageHeader title="Cadastro de produto" subtitle="Dados comerciais e tecnicos" action-label="Voltar" action-to="/products" />
    <v-alert v-if="errorMessage" type="error" variant="tonal" class="mb-4">{{ errorMessage }}</v-alert>
    <v-card rounded="xl">
      <v-card-text class="d-flex flex-column ga-4">
        <v-text-field v-model="form.code" label="Codigo" variant="outlined" />
        <v-text-field v-model="form.name" label="Nome" variant="outlined" />
        <v-text-field v-model="form.category" label="Categoria" variant="outlined" />
        <v-row>
          <v-col cols="12" md="4"><v-text-field v-model="form.unit" label="Unidade" variant="outlined" /></v-col>
          <v-col cols="12" md="4"><v-text-field v-model.number="form.base_price" type="number" label="Preco base" variant="outlined" /></v-col>
          <v-col cols="12" md="4"><v-select v-model="form.status" label="Status" variant="outlined" :items="['active', 'inactive']" /></v-col>
        </v-row>
        <v-textarea v-model="form.description" label="Descricao" variant="outlined" />
        <v-textarea v-model="form.technical_notes" label="Observacoes tecnicas" variant="outlined" />
        <div class="d-flex ga-3 justify-end">
          <v-btn variant="outlined" @click="router.push('/products')">Cancelar</v-btn>
          <v-btn color="primary" :loading="submitting" @click="submit">Salvar produto</v-btn>
        </div>
      </v-card-text>
    </v-card>
  </AppLayout>
</template>
