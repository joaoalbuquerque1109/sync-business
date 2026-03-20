<script setup lang="ts">
import { useRouter } from 'vue-router';
import { authApi } from '@/api/authApi';
import { useAuthStore } from '@/stores/auth';

defineProps<{ rail: boolean }>();
defineEmits<{ toggleSidebar: [] }>();

const router = useRouter();
const auth = useAuthStore();

async function logout() {
  try {
    await authApi.logout();
  } catch {
    // Ignore logout transport errors and clear the client session anyway.
  } finally {
    auth.clearSession();
    await router.push({ name: 'login' });
  }
}
</script>

<template>
  <v-app-bar color="primary" density="comfortable" elevation="2">
    <v-btn icon="mdi-menu" variant="text" @click="$emit('toggleSidebar')" />
    <v-toolbar-title class="font-weight-bold">Sistema Comercial</v-toolbar-title>
    <v-spacer />
    <v-text-field
      class="mr-4 d-none d-md-flex"
      density="compact"
      hide-details
      prepend-inner-icon="mdi-magnify"
      rounded="pill"
      variant="solo-filled"
      max-width="320"
      placeholder="Busca global"
    />
    <v-chip color="white" text-color="primary" prepend-icon="mdi-account">{{ auth.displayName }}</v-chip>
    <v-btn icon="mdi-logout" variant="text" @click="logout" />
  </v-app-bar>
</template>
