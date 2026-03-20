<script setup lang="ts">
const sections = [
  {
    title: 'Comercial',
    actions: ['Nova proposta', 'Propostas', 'Exportar Excel'],
  },
  {
    title: 'Cadastros',
    actions: ['Clientes', 'Produtos', 'Templates'],
  },
  {
    title: 'Gestão',
    actions: ['Usuários', 'Permissões', 'Auditoria'],
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
            :key="action"
            :title="action"
            prepend-icon="mdi-chevron-right-circle-outline"
          />
        </v-list>
      </v-card>
    </v-col>
  </v-row>
</template>
