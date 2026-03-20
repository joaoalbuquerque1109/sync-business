<script setup lang="ts">
defineProps<{ rail: boolean }>();
defineEmits<{ toggleSidebar: [] }>();
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
    <v-chip color="white" text-color="primary" prepend-icon="mdi-account">admin</v-chip>
  </v-app-bar>
</template>
