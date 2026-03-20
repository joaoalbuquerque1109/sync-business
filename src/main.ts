import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from './App.vue';
import router from './router';
import vuetify from './plugins/vuetify';
import { useAuthStore } from '@/stores/auth';
import '@mdi/font/css/materialdesignicons.css';

const app = createApp(App);
const pinia = createPinia();

app
  .use(pinia)
  .use(router)
  .use(vuetify);

useAuthStore(pinia).initialize();

app.mount('#app');
