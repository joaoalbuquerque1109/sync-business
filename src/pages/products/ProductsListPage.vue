<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import BasePageHeader from '@/components/common/BasePageHeader.vue';
</script>

<template>
  <AppLayout>
    <BasePageHeader title="Produtos" subtitle="Catálogo comercial e técnico" action-label="Novo produto" />
    <v-card rounded="xl">
      <v-card-text>
        <v-table>
          <thead>
            <tr><th>Código</th><th>Nome</th><th>Categoria</th><th>Preço base</th><th>Status</th></tr>
          </thead>
          <tbody>
            <tr><td>EQ-001</td><td>Equipamento Exemplo</td><td>Locação</td><td>R$ 1.500,00</td><td>Ativo</td></tr>
          </tbody>
        </v-table>
      </v-card-text>
    </v-card>
  </AppLayout>
</template>
