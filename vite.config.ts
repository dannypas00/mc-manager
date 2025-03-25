import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import vueDevTools from 'vite-plugin-vue-devtools';
import { resolve } from 'node:path';

export default defineConfig({
  plugins: [
    vueDevTools({
      launchEditor: 'phpstorm',
      appendTo: 'resources/js/app.ts',
    }),
    laravel({
      input: ['resources/js/app.ts', 'resources/css/app.css'],
      refresh: false,
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
  ],
  resolve: {
    alias: {
      '@': resolve(__dirname, 'resources/js'),
    },
  },
});
