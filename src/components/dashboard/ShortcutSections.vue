<script setup lang="ts">
import { useRouter } from 'vue-router';

const router = useRouter();

const sections = [
  {
    title: 'Comercial',
    actions: [
      { label: 'Nova proposta', to: '/proposals/new' },
      { label: 'Propostas', to: '/proposals' },
      { label: 'Exportar Excel', to: '/imports' },
    ],
  },
  {
    title: 'Cadastros',
    actions: [
      { label: 'Clientes', to: '/clients' },
      { label: 'Produtos', to: '/products' },
      { label: 'Templates', to: '/imports' },
    ],
  },
  {
    title: 'Gestao',
    actions: [
      { label: 'Usuarios', to: '/users' },
      { label: 'Permissoes', to: '/users' },
      { label: 'Auditoria', to: '/audits' },
    ],
  },
];
</script>

<template>
  <v-row>
    <v-col v-for="section in sections" :key="section.title" cols="12" md="4">
      <v-card rounded="xl" class="fill-height">
        <v-card-title>{{ section.title }}</v-card-title>
        <v-list>
          <v-list-item
            v-for="action in section.actions"
            :key="action.label"
            :title="action.label"
            prepend-icon="mdi-chevron-right-circle-outline"
            link
            @click="router.push(action.to)"
          />
        </v-list>
      </v-card>
    </v-col>
  </v-row>
</template>
