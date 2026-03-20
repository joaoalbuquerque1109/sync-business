<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import BasePageHeader from '@/components/common/BasePageHeader.vue';
</script>

<template>
  <AppLayout>
    <BasePageHeader title="Cadastro de produto" subtitle="Dados comerciais e técnicos" />
    <v-card rounded="xl">
      <v-card-text class="d-flex flex-column ga-4">
        <v-text-field label="Código" variant="outlined" />
        <v-text-field label="Nome" variant="outlined" />
        <v-text-field label="Categoria" variant="outlined" />
        <v-text-field label="Unidade" variant="outlined" />
        <v-text-field label="Preço base" variant="outlined" />
        <v-textarea label="Observações técnicas" variant="outlined" />
      </v-card-text>
    </v-card>
  </AppLayout>
</template>
