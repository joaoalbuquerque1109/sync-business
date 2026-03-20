<template>
  <v-main class="auth-layout d-flex align-center justify-center pa-6">
    <slot />
  </v-main>
</template>

<style scoped>
.auth-layout {
  min-height: 100vh;
  background: linear-gradient(135deg, #dff6f3 0%, #c2ebe4 45%, #f4f7f9 100%);
}
</style>
