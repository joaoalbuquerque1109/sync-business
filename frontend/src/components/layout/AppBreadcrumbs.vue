<script setup lang="ts">
import { computed } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();

const items = computed(() => {
  const segments = route.path.split('/').filter(Boolean);
  if (!segments.length) {
    return [{ title: 'Dashboard', disabled: true }];
  }

  return segments.map((segment, index) => ({
    title: segment.charAt(0).toUpperCase() + segment.slice(1),
    disabled: index === segments.length - 1,
  }));
});
</script>

<template>
  <v-breadcrumbs :items="items" class="px-0" />
</template>
