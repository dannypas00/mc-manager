<template>
  <PortalTarget name="loader" />
  <div class="min-h-full">
    <Disclosure v-slot="{ open }" as="nav" class="bg-emerald-800">
      <div class="mx-auto px-4 md:px-6">
        <div class="flex h-16 items-center justify-between">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <img class="h-10 w-10" :src="ApplicationLogo" :alt="appName" />
            </div>
            <div class="hidden md:block">
              <div class="ml-10 flex items-baseline space-x-4">
                <a
                  v-for="item in navigation"
                  :key="item.name"
                  :href="item.href"
                  :class="[
                    item.current
                      ? 'bg-emerald-950 text-white'
                      : 'text-emerald-100 hover:bg-emerald-700 hover:text-white',
                    'rounded-md px-3 py-2 text-sm font-medium',
                  ]"
                  :aria-current="item.current ? 'page' : undefined"
                  >{{ item.name }}</a
                >
              </div>
            </div>
          </div>
          <div class="hidden md:block">
            <div class="ml-4 flex items-center md:ml-6">
              <button
                type="button"
                class="relative rounded-full bg-emerald-900 p-1 text-emerald-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
              >
                <span class="absolute -inset-1.5" />
                <span
                  v-t="'layout.navigation.notifications.view'"
                  class="sr-only"
                />
                <BellIcon class="h-6 w-6" aria-hidden="true" />
              </button>

              <!-- Profile dropdown -->
              <Menu as="div" class="relative ml-3">
                <div>
                  <MenuButton
                    class="relative flex max-w-xs items-center rounded-full bg-emerald-950 text-sm text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-emerald-800"
                  >
                    <span class="absolute -inset-1.5" />
                    <span v-t="'layout.navigation.user.view'" class="sr-only" />
                    <img
                      class="h-8 w-8 rounded-full"
                      :src="userStore.user?.icon"
                      alt=""
                    />
                  </MenuButton>
                </div>
                <transition
                  enter-active-class="transition ease-out duration-100"
                  enter-from-class="transform opacity-0 scale-95"
                  enter-to-class="transform opacity-100 scale-100"
                  leave-active-class="transition ease-in duration-75"
                  leave-from-class="transform opacity-100 scale-100"
                  leave-to-class="transform opacity-0 scale-95"
                >
                  <MenuItems
                    class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                  >
                    <MenuItem
                      v-for="item in userNavigation"
                      :key="item.name"
                      v-slot="{ active }"
                    >
                      <a
                        :href="item.href"
                        :class="[
                          active ? 'bg-emerald-100' : '',
                          'block px-4 py-2 text-sm text-emerald-500',
                        ]"
                      >
                        {{ item.name }}
                      </a>
                    </MenuItem>
                  </MenuItems>
                </transition>
              </Menu>
            </div>
          </div>
          <div class="flex md:hidden">
            <!-- Mobile menu button -->
            <DisclosureButton
              class="relative inline-flex items-center justify-center rounded-md bg-emerald-950 p-2 text-emerald-500 focus:text-white focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2 focus:ring-offset-emerald-600"
            >
              <span class="absolute -inset-0.5" />
              <span v-t="'layout.navigation.menu.open'" class="sr-only" />
              <Bars3Icon
                v-if="!open"
                class="block h-6 w-6"
                aria-hidden="true"
              />
              <XMarkIcon v-else class="block h-6 w-6" aria-hidden="true" />
            </DisclosureButton>
          </div>
        </div>
      </div>

      <DisclosurePanel class="md:hidden">
        <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
          <DisclosureButton
            v-for="item in navigation"
            :key="item.name"
            as="a"
            :href="item.href"
            :class="[
              item.current
                ? 'bg-emerald-950 text-white'
                : 'text-emerald-100 hover:bg-emerald-700 hover:text-white',
              'block rounded-md px-3 py-2 text-base font-medium',
            ]"
            :aria-current="item.current ? 'page' : undefined"
          >
            {{ item.name }}
          </DisclosureButton>
        </div>
        <div class="border-t border-gray-700 pb-3 pt-4">
          <div class="flex items-center px-5">
            <div class="flex-shrink-0">
              <img
                class="h-10 w-10 rounded-full"
                :src="this.userStore.user?.icon"
                alt=""
              />
            </div>
            <div class="ml-3">
              <div class="text-base font-medium text-white">
                {{ this.userStore.user?.name }}
              </div>
              <div class="text-sm font-medium text-emerald-200">
                {{ this.userStore.user?.email }}
              </div>
            </div>
            <button
              type="button"
              class="relative ml-auto flex-shrink-0 rounded-full bg-emerald-950 p-1 text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-emerald-900"
            >
              <span class="absolute -inset-1.5" />
              <span
                v-t="'layout.navigation.notifications.view'"
                class="sr-only"
              />
              <BellIcon class="h-6 w-6" aria-hidden="true" />
            </button>
          </div>
          <div class="mt-3 space-y-1 px-2">
            <DisclosureButton
              v-for="item in userNavigation"
              :key="item.name"
              as="a"
              :href="item.href"
              class="block rounded-md px-3 py-2 text-base font-medium text-emerald-100 hover:bg-gray-700 hover:text-white"
            >
              {{ item.name }}
            </DisclosureButton>
          </div>
        </div>
      </DisclosurePanel>
    </Disclosure>

    <header class="bg-white shadow-sm">
      <PortalTarget name="main-layout-header" />
    </header>

    <main>
      <div class="mx-auto px-2 py-4 text-slate-900 sm:px-6 sm:py-6 lg:px-8">
        <slot />
      </div>
    </main>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import {
  Disclosure,
  DisclosureButton,
  DisclosurePanel,
  Menu,
  MenuButton,
  MenuItem,
  MenuItems,
} from '@headlessui/vue';
import { Bars3Icon, BellIcon, XMarkIcon } from '@heroicons/vue/24/outline';
import ApplicationLogo from '../../images/icons/MCM-logo.webp';
import { useUserStore } from '../Stores/UserStore';
import { PortalTarget } from 'portal-vue';
import User = App.Models.User;

export default defineComponent({
  components: {
    PortalTarget,
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    Bars3Icon,
    BellIcon,
    XMarkIcon,
  },

  inheritAttrs: true,

  props: {
    auth: {
      type: Object as PropType<{ user: User }>,
      required: true,
    },
  },

  data() {
    return {
      ApplicationLogo,

      navigation: [
        {
          name: this.$t('pages.dashboard.menu_title'),
          href: route('home'),
          current: route().current('home'),
        },
        {
          name: this.$t('pages.servers.menu_title'),
          href: route('servers.index'),
          current: route().current('servers.index'),
        },
      ],

      userNavigation: [
        { name: 'Your Profile', href: '#' },
        { name: 'Settings', href: '#' },
        { name: 'Sign out', href: route('auth.logout') },
      ],

      appName: import.meta.env.VITE_APP_NAME,

      userStore: useUserStore(),
    };
  },

  beforeMount() {
    useUserStore().user = this.auth.user;
  },
});
</script>
