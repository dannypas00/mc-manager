import './bootstrap';
import 'vue-toastification/dist/index.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { InertiaProgress } from '@inertiajs/progress';
import PortalVue from 'portal-vue';
import MainLayout from './Layouts/MainLayout.vue';
import i18n from './i18n';
import { createPinia } from 'pinia';
import VueToastificationPlugin, { PluginOptions, POSITION, useToast } from 'vue-toastification';
import { autoAnimatePlugin } from '@formkit/auto-animate/vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { fab } from '@fortawesome/free-brands-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';

library.add(fas, fab, far);

InertiaProgress.init({
  delay: 250,
  color: '#29d',
  includeCSS: true,
  showSpinner: false,
});

const toastOptions: PluginOptions = {
  position: POSITION.TOP_RIGHT,
};

createInertiaApp({
  resolve: name => {
    const page = resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob('./Pages/**/*.vue'),
    );
    page.then(module => {
      module.default.layout = module.default.layout ?? MainLayout;
    });
    return page;
  },
  setup ({ el, App, props, plugin }) {
    const vue = createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(i18n)
      .use(PortalVue)
      .use(createPinia())
      .use(autoAnimatePlugin)
      .use(VueToastificationPlugin, toastOptions);

    vue.config.globalProperties.$route = route;
    vue.config.globalProperties.$toast = useToast();

    vue.mount(el);
  },
});
