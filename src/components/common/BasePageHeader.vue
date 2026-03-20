<script setup lang="ts">
import { useRouter } from 'vue-router';

const router = useRouter();

const props = defineProps<{
  title: string;
  subtitle?: string;
  actionLabel?: string;
  actionTo?: string;
}>();

function handleAction() {
  if (props.actionTo) {
    router.push(props.actionTo);
  }
}
</script>

<template>
  <div class="d-flex align-center justify-space-between mb-6 ga-4 flex-wrap">
    <div>
      <div class="text-h5 font-weight-bold">{{ title }}</div>
      <div v-if="subtitle" class="text-medium-emphasis">{{ subtitle }}</div>
    </div>
    <v-btn v-if="actionLabel" color="primary" prepend-icon="mdi-plus" @click="handleAction">{{ actionLabel }}</v-btn>
  </div>
</template>
