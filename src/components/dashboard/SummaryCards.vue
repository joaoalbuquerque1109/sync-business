<script setup lang="ts">
const cards = [
  { title: 'Total de Propostas', value: '124', icon: 'mdi-file-chart-outline' },
  { title: 'Em Elaboração', value: '36', icon: 'mdi-progress-pencil' },
  { title: 'Clientes Recentes', value: '18', icon: 'mdi-account-clock-outline' },
  { title: 'Produtos Ativos', value: '352', icon: 'mdi-package-variant' },
];
</script>

<template>
  <v-row>
    <v-col v-for="card in cards" :key="card.title" cols="12" sm="6" xl="3">
      <v-card rounded="xl" elevation="1">
        <v-card-text class="d-flex align-center justify-space-between">
          <div>
            <div class="text-overline text-medium-emphasis">{{ card.title }}</div>
            <div class="text-h5 font-weight-bold">{{ card.value }}</div>
          </div>
          <v-avatar color="secondary" size="44">
            <v-icon :icon="card.icon" />
          </v-avatar>
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>
</template>
