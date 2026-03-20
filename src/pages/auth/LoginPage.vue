<script setup lang="ts">
import axios from 'axios';
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { authApi } from '@/api/authApi';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const auth = useAuthStore();
const showPassword = ref(false);
const loading = ref(false);
const errorMessage = ref('');
const form = reactive({ login: 'admin', password: 'admin' });

async function submit() {
  loading.value = true;
  errorMessage.value = '';

  try {
    const response = await authApi.login({
      login: form.login,
      password: form.password,
    });

    auth.setSession({
      token: response.data.data.token,
      user: response.data.data.user,
    });

    await router.push({ name: 'dashboard' });
  } catch (error) {
    if (axios.isAxiosError(error)) {
      errorMessage.value = error.response?.data?.message || 'Nao foi possivel entrar.';
    } else {
      errorMessage.value = 'Nao foi possivel entrar.';
    }
  } finally {
    loading.value = false;
  }
}
</script>

<template>
  <AuthLayout>
    <v-card class="mx-auto" max-width="460" min-width="320" rounded="xl" elevation="8">
      <v-card-text class="pa-8">
        <div class="text-h4 text-center font-weight-bold mb-6">Login</div>
        <v-form class="d-flex flex-column ga-4" @submit.prevent="submit">
          <v-alert v-if="errorMessage" type="error" variant="tonal" density="comfortable">{{ errorMessage }}</v-alert>
          <v-text-field v-model="form.login" label="E-mail ou usuario" variant="outlined" prepend-inner-icon="mdi-account-outline" />
          <v-text-field
            v-model="form.password"
            label="Senha"
            :type="showPassword ? 'text' : 'password'"
            variant="outlined"
            prepend-inner-icon="mdi-lock-outline"
          />
          <v-checkbox v-model="showPassword" label="Mostrar senha" hide-details />
          <v-btn color="secondary" size="large" block rounded="pill" type="submit" :loading="loading">Entrar</v-btn>
          <div class="d-flex justify-space-between text-body-2 mt-2">
            <a href="#">Recuperar senha</a>
            <a href="#">Cadastrar</a>
          </div>
        </v-form>
      </v-card-text>
    </v-card>
  </AuthLayout>
</template>
