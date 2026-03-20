<script setup lang="ts">
import axios from 'axios';
import { onMounted, reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { rolesApi } from '@/api/rolesApi';
import { usersApi } from '@/api/usersApi';
import BasePageHeader from '@/components/common/BasePageHeader.vue';
import AppLayout from '@/layouts/AppLayout.vue';

const router = useRouter();
const submitting = ref(false);
const loadingRoles = ref(false);
const errorMessage = ref('');
const roles = ref<Array<{ id: number; name: string }>>([]);

const form = reactive({
  name: '',
  username: '',
  email: '',
  password: '',
  status: 'active',
  role_ids: [] as number[],
});

function extractCollection(payload: unknown) {
  if (Array.isArray(payload)) return payload;
  if (payload && typeof payload === 'object' && 'data' in payload && Array.isArray((payload as { data: unknown }).data)) {
    return (payload as { data: unknown[] }).data;
  }
  return [];
}

async function loadRoles() {
  loadingRoles.value = true;
  try {
    const response = await rolesApi.list();
    roles.value = extractCollection(response.data.data) as Array<{ id: number; name: string }>;
  } finally {
    loadingRoles.value = false;
  }
}

async function submit() {
  submitting.value = true;
  errorMessage.value = '';
  try {
    await usersApi.create(form);
    await router.push('/users');
  } catch (error) {
    if (axios.isAxiosError(error)) {
      errorMessage.value = error.response?.data?.message || 'Nao foi possivel salvar o usuario.';
    } else {
      errorMessage.value = 'Nao foi possivel salvar o usuario.';
    }
  } finally {
    submitting.value = false;
  }
}

onMounted(loadRoles);
</script>

<template>
  <AppLayout>
    <BasePageHeader title="Novo usuario" subtitle="Cadastro de acesso e papeis" action-label="Voltar" action-to="/users" />
    <v-alert v-if="errorMessage" type="error" variant="tonal" class="mb-4">{{ errorMessage }}</v-alert>
    <v-card rounded="xl">
      <v-card-text class="d-flex flex-column ga-4">
        <v-text-field v-model="form.name" label="Nome" variant="outlined" />
        <v-text-field v-model="form.username" label="Usuario" variant="outlined" />
        <v-text-field v-model="form.email" label="E-mail" variant="outlined" />
        <v-text-field v-model="form.password" type="password" label="Senha" variant="outlined" />
        <v-select v-model="form.status" label="Status" variant="outlined" :items="['active', 'inactive']" />
        <v-select v-model="form.role_ids" label="Papeis" variant="outlined" :items="roles" item-title="name" item-value="id" multiple chips :loading="loadingRoles" />
        <div class="d-flex ga-3 justify-end">
          <v-btn variant="outlined" @click="router.push('/users')">Cancelar</v-btn>
          <v-btn color="primary" :loading="submitting" @click="submit">Salvar usuario</v-btn>
        </div>
      </v-card-text>
    </v-card>
  </AppLayout>
</template>
