import './bootstrap';
import 'vue-toastification/dist/index.css';

import { createApp, DefineComponent, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import PortalVue from 'portal-vue';
import i18n from './i18n';
import { createPinia } from 'pinia';
import VueToastificationPlugin, { PluginOptions, POSITION, useToast } from 'vue-toastification';
import { library } from '@fortawesome/fontawesome-svg-core';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { fab } from '@fortawesome/free-brands-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import MainLayout from './Layouts/MainLayout.vue';
// import BrandedStackedLayout from './Layouts/BrandedStackedLayout.vue';

library.add(fas, fab, far);

const toastOptions: PluginOptions = {
  position: POSITION.TOP_RIGHT,
};

// noinspection JSIgnoredPromiseFromCall
createInertiaApp({
  title: title =>
    `${title} - ${import.meta.env.VITE_APP_NAME || 'APP NAME NOT SET'}`,
  resolve: name => {
    // @ts-expect-error: Type too complex to define
    const page: Promise<DefineComponent> = resolvePageComponent(
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
      .use(VueToastificationPlugin, toastOptions);

    vue.config.globalProperties.$route = route;
    vue.config.globalProperties.$toast = useToast();

    vue.mount(el);
  },
  progress: {
    delay: 250,
    color: '#29d',
    includeCSS: true,
    showSpinner: false,
  },
});
