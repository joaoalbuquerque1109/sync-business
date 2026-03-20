<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import BasePageHeader from '@/components/common/BasePageHeader.vue';
</script>

<template>
  <AppLayout>
    <BasePageHeader title="Usuários e permissões" subtitle="RBAC inicial com papéis e ações por módulo" action-label="Novo usuário" />
    <v-card rounded="xl">
      <v-card-text>
        <v-tabs>
          <v-tab>Usuários</v-tab>
          <v-tab>Papéis</v-tab>
          <v-tab>Permissões</v-tab>
        </v-tabs>
      </v-card-text>
    </v-card>
  </AppLayout>
</template>
