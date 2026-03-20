<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import BasePageHeader from '@/components/common/BasePageHeader.vue';
</script>

<template>
  <AppLayout>
    <BasePageHeader title="Clientes" subtitle="Listagem com filtros e busca" action-label="Novo cliente" />
    <v-card rounded="xl">
      <v-card-text>
        <v-text-field label="Buscar por nome ou CNPJ" prepend-inner-icon="mdi-magnify" variant="outlined" class="mb-4" />
        <v-table>
          <thead>
            <tr><th>Razão social</th><th>Fantasia</th><th>CNPJ</th><th>UF</th><th>Status</th></tr>
          </thead>
          <tbody>
            <tr><td>Cliente Exemplo</td><td>Cliente X</td><td>00.000.000/0001-00</td><td>SP</td><td>Ativo</td></tr>
          </tbody>
        </v-table>
      </v-card-text>
    </v-card>
  </AppLayout>
</template>
