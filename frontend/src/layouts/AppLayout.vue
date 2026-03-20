<script setup lang="ts">
import { ref } from 'vue';
import AppSidebar from '@/components/layout/AppSidebar.vue';
import AppTopbar from '@/components/layout/AppTopbar.vue';
import AppBreadcrumbs from '@/components/layout/AppBreadcrumbs.vue';

const rail = ref(false);
</script>

<template>
  <AppSidebar :rail="rail" />
  <AppTopbar :rail="rail" @toggle-sidebar="rail = !rail" />
  <v-main>
    <v-container fluid class="pa-6">
      <AppBreadcrumbs />
      <slot />
    </v-container>
  </v-main>
</template>
