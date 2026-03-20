<script setup lang="ts">
import axios from 'axios';
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { clientsApi } from '@/api/clientsApi';
import BasePageHeader from '@/components/common/BasePageHeader.vue';
import AppLayout from '@/layouts/AppLayout.vue';

const router = useRouter();
const submitting = ref(false);
const errorMessage = ref('');

const form = reactive({
  company_name: '',
  trade_name: '',
  cnpj: '',
  email: '',
  phone: '',
  city: '',
  state: '',
  notes: '',
  is_active: true,
});

async function submit() {
  submitting.value = true;
  errorMessage.value = '';

  try {
    await clientsApi.create(form);
    await router.push('/clients');
  } catch (error) {
    if (axios.isAxiosError(error)) {
      errorMessage.value = error.response?.data?.message || 'Nao foi possivel salvar o cliente.';
    } else {
      errorMessage.value = 'Nao foi possivel salvar o cliente.';
    }
  } finally {
    submitting.value = false;
  }
}
</script>

<template>
  <AppLayout>
    <BasePageHeader title="Cadastro de cliente" subtitle="Dados gerais, contato e endereco" action-label="Voltar" action-to="/clients" />
    <v-alert v-if="errorMessage" type="error" variant="tonal" class="mb-4">{{ errorMessage }}</v-alert>
    <v-row>
      <v-col cols="12" md="8">
        <v-card rounded="xl">
          <v-card-text class="d-flex flex-column ga-4">
            <v-text-field v-model="form.company_name" label="Razao social" variant="outlined" />
            <v-text-field v-model="form.trade_name" label="Nome fantasia" variant="outlined" />
            <v-text-field v-model="form.cnpj" label="CNPJ" variant="outlined" />
            <v-text-field v-model="form.email" label="E-mail" variant="outlined" />
            <v-text-field v-model="form.phone" label="Telefone" variant="outlined" />
            <v-row>
              <v-col cols="12" md="8"><v-text-field v-model="form.city" label="Cidade" variant="outlined" /></v-col>
              <v-col cols="12" md="4"><v-text-field v-model="form.state" label="UF" variant="outlined" maxlength="2" /></v-col>
            </v-row>
            <v-textarea v-model="form.notes" label="Observacoes" variant="outlined" />
            <v-switch v-model="form.is_active" label="Cliente ativo" color="primary" hide-details />
            <div class="d-flex ga-3 justify-end">
              <v-btn variant="outlined" @click="router.push('/clients')">Cancelar</v-btn>
              <v-btn color="primary" :loading="submitting" @click="submit">Salvar cliente</v-btn>
            </div>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </AppLayout>
</template>
