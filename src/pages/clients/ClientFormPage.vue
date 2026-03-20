<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import BasePageHeader from '@/components/common/BasePageHeader.vue';
</script>

<template>
  <AppLayout>
    <BasePageHeader title="Cadastro de cliente" subtitle="Dados gerais, contato e endereço" />
    <v-row>
      <v-col cols="12" md="8">
        <v-card rounded="xl">
          <v-card-text class="d-flex flex-column ga-4">
            <v-text-field label="Razão social" variant="outlined" />
            <v-text-field label="Nome fantasia" variant="outlined" />
            <v-text-field label="CNPJ" variant="outlined" />
            <v-text-field label="E-mail" variant="outlined" />
            <v-text-field label="Telefone" variant="outlined" />
            <v-textarea label="Observações" variant="outlined" />
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </AppLayout>
</template>
