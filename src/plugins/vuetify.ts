import 'vuetify/styles';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import { aliases, mdi } from 'vuetify/iconsets/mdi';

export default createVuetify({
  components,
  directives,
  theme: {
    defaultTheme: 'corporate',
    themes: {
      corporate: {
        dark: false,
        colors: {
          primary: '#b71c1c',
          secondary: '#0f766e',
          accent: '#e53935',
          background: '#f5f7fa',
          surface: '#ffffff',
        },
      },
    },
  },
  icons: {
    defaultSet: 'mdi',
    aliases,
    sets: { mdi },
  },
});
