import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { InertiaProgress } from '@inertiajs/progress';
import PortalVue from 'portal-vue';
import MainLayout from './Layouts/MainLayout.vue';
import i18n from './i18n.ts';
import route from 'ziggy-js/src/js/index.js';

InertiaProgress.init({
  delay: 250,
  color: '#29d',
  includeCSS: true,
  showSpinner: false,
});

createInertiaApp({
  resolve: name => {
    const page = resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob('./Pages/**/*.vue'),
    );
    page.then(module => {
      module.default.layout = module.default.layout || MainLayout;
    });
    return page;
  },
  setup ({ el, App, props, plugin }) {
    const vue = createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(i18n)
      .use(PortalVue);

    vue.config.globalProperties.$route = route;

    vue.mount(el);
  },
});
