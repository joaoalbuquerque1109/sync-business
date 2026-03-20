<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import BasePageHeader from '@/components/common/BasePageHeader.vue';
</script>

<template>
  <AppLayout>
    <BasePageHeader title="Propostas" subtitle="Pipeline comercial e exportações" action-label="Nova proposta" />
    <v-card rounded="xl">
      <v-card-text>
        <v-table>
          <thead>
            <tr><th>Número</th><th>Cliente</th><th>Status</th><th>Validade</th><th>Total</th></tr>
          </thead>
          <tbody>
            <tr><td>PROP-0001</td><td>Cliente Exemplo</td><td>Em elaboração</td><td>30/03/2026</td><td>R$ 12.300,00</td></tr>
          </tbody>
        </v-table>
      </v-card-text>
    </v-card>
  </AppLayout>
</template>
