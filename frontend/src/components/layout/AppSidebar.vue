<script setup lang="ts">
const items = [
  { title: 'Dashboard', icon: 'mdi-view-dashboard-outline', to: '/' },
  { title: 'Clientes', icon: 'mdi-account-group-outline', to: '/clients' },
  { title: 'Produtos', icon: 'mdi-package-variant-closed', to: '/products' },
  { title: 'Propostas', icon: 'mdi-file-document-edit-outline', to: '/proposals' },
  { title: 'Importações', icon: 'mdi-file-excel-outline', to: '/imports' },
  { title: 'Usuários', icon: 'mdi-account-cog-outline', to: '/users' },
  { title: 'Auditoria', icon: 'mdi-history', to: '/audits' },
];

defineProps<{ rail: boolean }>();
</script>

<template>
  <v-navigation-drawer :rail="rail" permanent color="surface" elevation="2">
    <v-list nav density="comfortable">
      <v-list-item title="Sync Business" subtitle="ERP Comercial" prepend-icon="mdi-domain" />
      <v-divider class="my-2" />
      <v-list-item
        v-for="item in items"
        :key="item.title"
        :title="item.title"
        :prepend-icon="item.icon"
        :to="item.to"
        rounded="lg"
      />
    </v-list>
  </v-navigation-drawer>
</template>
