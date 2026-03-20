<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import BasePageHeader from '@/components/common/BasePageHeader.vue';
</script>

<template>
  <AppLayout>
    <BasePageHeader title="Importação Excel" subtitle="Clientes, produtos e pré-preenchimento de propostas" />
    <v-row>
      <v-col cols="12" md="4" v-for="card in ['Clientes', 'Produtos', 'Template/Pré-preenchimento']" :key="card">
        <v-card rounded="xl" class="fill-height">
          <v-card-title>{{ card }}</v-card-title>
          <v-card-text>
            <v-file-input label="Selecionar planilha" variant="outlined" prepend-icon="mdi-file-excel-outline" />
            <v-btn color="primary" class="mt-3">Importar</v-btn>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </AppLayout>
</template>
