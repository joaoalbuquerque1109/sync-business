<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import BasePageHeader from '@/components/common/BasePageHeader.vue';
</script>

<template>
  <AppLayout>
    <BasePageHeader title="Nova proposta" subtitle="Cliente, itens, condições e totais" />
    <v-row>
      <v-col cols="12" lg="8">
        <v-card rounded="xl" class="mb-4">
          <v-card-text class="d-flex flex-column ga-4">
            <v-select label="Cliente" variant="outlined" :items="['Cliente Exemplo']" />
            <v-select label="Template" variant="outlined" :items="['Template padrão']" />
            <v-row>
              <v-col cols="12" md="6"><v-text-field label="Data" variant="outlined" /></v-col>
              <v-col cols="12" md="6"><v-text-field label="Validade" variant="outlined" /></v-col>
            </v-row>
          </v-card-text>
        </v-card>
        <v-card rounded="xl">
          <v-card-title>Itens da proposta</v-card-title>
          <v-card-text>
            <v-table>
              <thead>
                <tr><th>Item</th><th>Qtd.</th><th>Preço unit.</th><th>Desconto</th><th>Total</th></tr>
              </thead>
              <tbody>
                <tr><td>Equipamento Exemplo</td><td>2</td><td>R$ 500,00</td><td>R$ 0,00</td><td>R$ 1.000,00</td></tr>
              </tbody>
            </v-table>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="12" lg="4">
        <v-card rounded="xl">
          <v-card-title>Resumo</v-card-title>
          <v-card-text>
            <div class="d-flex justify-space-between mb-2"><span>Subtotal</span><strong>R$ 1.000,00</strong></div>
            <div class="d-flex justify-space-between mb-2"><span>Desconto</span><strong>R$ 0,00</strong></div>
            <div class="d-flex justify-space-between"><span>Total</span><strong>R$ 1.000,00</strong></div>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </AppLayout>
</template>
