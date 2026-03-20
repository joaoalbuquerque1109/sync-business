<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import BasePageHeader from '@/components/common/BasePageHeader.vue';
</script>

<template>
  <AppLayout>
    <BasePageHeader title="Auditoria" subtitle="Histórico de alterações críticas" />
    <v-card rounded="xl">
      <v-card-text>
        <v-table>
          <thead>
            <tr><th>Ação</th><th>Entidade</th><th>Usuário</th><th>Data</th></tr>
          </thead>
          <tbody>
            <tr><td>created</td><td>proposal</td><td>admin</td><td>20/03/2026 14:00</td></tr>
          </tbody>
        </v-table>
      </v-card-text>
    </v-card>
  </AppLayout>
</template>
