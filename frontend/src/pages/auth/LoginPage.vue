<script setup lang="ts">
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const auth = useAuthStore();
const showPassword = ref(false);
const form = reactive({ login: 'admin', password: 'admin' });

function submit() {
  auth.setSession({
    token: 'dev-token',
    user: { name: 'Administrador', email: 'admin@local.test' },
  });

  router.push({ name: 'dashboard' });
}
</script>

<template>
  <AuthLayout>
    <v-card class="mx-auto" max-width="460" min-width="320" rounded="xl" elevation="8">
      <v-card-text class="pa-8">
        <div class="text-h4 text-center font-weight-bold mb-6">Login</div>
        <v-form class="d-flex flex-column ga-4" @submit.prevent="submit">
          <v-text-field v-model="form.login" label="E-mail ou usuário" variant="outlined" prepend-inner-icon="mdi-account-outline" />
          <v-text-field
            v-model="form.password"
            label="Senha"
            :type="showPassword ? 'text' : 'password'"
            variant="outlined"
            prepend-inner-icon="mdi-lock-outline"
          />
          <v-checkbox v-model="showPassword" label="Mostrar senha" hide-details />
          <v-btn color="secondary" size="large" block rounded="pill" type="submit">Entrar</v-btn>
          <div class="d-flex justify-space-between text-body-2 mt-2">
            <a href="#">Recuperar senha</a>
            <a href="#">Cadastrar</a>
          </div>
        </v-form>
      </v-card-text>
    </v-card>
  </AuthLayout>
</template>
