<template>
  <MainLayout>
    <PageTitle :title="$t('pages.servers.show.title', { name: store.model.name })" :header="store.model.name"/>

    <div class="mx-auto lg:flex lg:gap-x-16 lg:px-8">
      <aside class="flex overflow-x-auto border-b border-gray-900/5 py-4 lg:block lg:w-64 lg:flex-none lg:border-0 lg:py-20">
        <nav class="flex-none px-4 sm:px-6 lg:px-0">
          <ul role="list" class="flex gap-x-3 gap-y-1 whitespace-nowrap lg:flex-col">
            <li v-for="item in nav" :key="item.name">
              <a
                :class="$route().current().startsWith(item.route) ? 'bg-gray-50 text-emerald-500' : 'text-gray-700 hover:text-emerald-500 hover:bg-gray-50'"
                class="group flex gap-x-3 rounded-md py-2 pl-2 pr-3 text-sm leading-6 font-semibold"
                role="button"
              >
                <Component
                  :is="item.icon"
                  :class="[$route().current().startsWith(item.route) ? 'text-emerald-500' : 'text-gray-400 group-hover:text-emerald-500', 'h-6 w-6 shrink-0']"
                  aria-hidden="true"
                />
                {{ item.name }}
              </a>
            </li>
          </ul>
        </nav>
      </aside>

      <main class="px-4 pb-16 sm:px-6 lg:flex-auto lg:px-0 lg:pb-20">
        <slot/>
      </main>
    </div>
  </MainLayout>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { Cog8ToothIcon, FolderOpenIcon, UserCircleIcon } from '@heroicons/vue/24/outline';
import { useServerShowStore } from '../../Stores/Servers/ServerShowStore';
import PageTitle from '../../Components/Layout/PageTitle.vue';
import MainLayout from '../../Layouts/MainLayout.vue';

export default defineComponent({
  components: { MainLayout, PageTitle },

  data () {
    return {
      store: useServerShowStore(),
      nav: [
        {
          name: this.$t('pages.servers.show.console.nav_title'),
          icon: UserCircleIcon,
          route: '',
        },
        {
          name: this.$t('pages.servers.show.files.nav_title'),
          icon: FolderOpenIcon,
          route: '',
        },
        {
          name: this.$t('pages.servers.show.settings.nav_title'),
          icon: Cog8ToothIcon,
          route: '',
        },
      ],
    };
  },

  mounted () {
    this.store.getServer(this.$attrs.route_parameters.id);
  },
});
</script>
