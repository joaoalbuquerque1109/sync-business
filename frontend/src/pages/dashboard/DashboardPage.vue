<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import BasePageHeader from '@/components/common/BasePageHeader.vue';
import GlobalSearchBar from '@/components/layout/GlobalSearchBar.vue';
import SummaryCards from '@/components/dashboard/SummaryCards.vue';
import ShortcutSections from '@/components/dashboard/ShortcutSections.vue';
import ReminderPanel from '@/components/dashboard/ReminderPanel.vue';
</script>

<template>
  <AppLayout>
    <BasePageHeader title="Dashboard" subtitle="Portal administrativo/comercial" action-label="Nova proposta" />
    <GlobalSearchBar />
    <SummaryCards />
    <v-row class="mt-2">
      <v-col cols="12" lg="8">
        <ShortcutSections />
      </v-col>
      <v-col cols="12" lg="4">
        <ReminderPanel />
      </v-col>
    </v-row>
  </AppLayout>
</template>
