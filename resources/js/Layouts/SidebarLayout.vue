<template>
  <div>
    <TransitionRoot as="template" :show="sidebarOpen">
      <Dialog as="div" class="relative z-50 lg:hidden" @close="sidebarOpen = false">
        <TransitionChild
          as="template"
          enter="transition-opacity ease-linear duration-300"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="transition-opacity ease-linear duration-300"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-gray-900/80"/>
        </TransitionChild>

        <div class="fixed inset-0 flex">
          <TransitionChild
            as="template"
            enter="transition ease-in-out duration-300 transform"
            enter-from="-translate-x-full"
            enter-to="translate-x-0"
            leave="transition ease-in-out duration-300 transform"
            leave-from="translate-x-0"
            leave-to="-translate-x-full"
          >
            <DialogPanel class="relative mr-16 flex w-full max-w-xs flex-1">
              <TransitionChild
                as="template"
                enter="ease-in-out duration-300"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in-out duration-300"
                leave-from="opacity-100"
                leave-to="opacity-0"
              >
                <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                  <button type="button" class="-m-2.5 p-2.5" @click="sidebarOpen = false">
                    <span class="sr-only">Close sidebar</span>
                    <XMarkIcon class="h-6 w-6 text-white" aria-hidden="true"/>
                  </button>
                </div>
              </TransitionChild>
              <!-- Sidebar component, swap this element with another sidebar if you like -->
              <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-brand-hover px-6 pb-2">
                <div class="flex h-16 shrink-0 items-center">
                  <img
                    class="h-8 w-auto"
                    :src="appLogo"
                    :alt="appName"
                  />
                </div>
                <nav class="flex flex-1 flex-col">
                  <ul role="list" class="flex flex-1 flex-col gap-y-7">
                    <li>
                      <ul role="list" class="-mx-2 space-y-1">
                        <SidebarNavEntry v-for="item in navigation" :key="item.name" :item="item"/>
                      </ul>
                    </li>
                    <li v-if="teams.length">
                      <div class="text-xs font-semibold leading-6 text-brand-light">Your teams</div>
                      <ul role="list" class="-mx-2 mt-2 space-y-1">
                        <li v-for="team in teams" :key="team.name">
                          <a
                            :href="team.href"
                            :class="[team.current ? 'bg-brand text-white' : 'text-brand-light hover:text-white hover:bg-brand', 'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold']"
                          >
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg border border-brand-light bg-brand text-[0.625rem] font-medium text-white">
                              {{ team.initial }}
                            </span>
                            <span class="truncate">{{ team.name }}</span>
                          </a>
                        </li>
                      </ul>
                    </li>
                  </ul>
                </nav>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </Dialog>
    </TransitionRoot>

    <!-- Static sidebar for desktop -->
    <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
      <!-- Sidebar component, swap this element with another sidebar if you like -->
      <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-brand px-6">
        <div class="flex h-16 shrink-0 items-center">
          <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=white" alt="Your Company"/>
        </div>
        <nav class="flex flex-1 flex-col">
          <ul role="list" class="flex flex-1 flex-col gap-y-7">
            <li>
              <ul role="list" class="-mx-2 space-y-1">
                <SidebarNavEntry v-for="item in navigation" :key="item.name" :item="item"/>
              </ul>
            </li>

            <li v-if="teams.length">
              <div class="text-xs font-semibold leading-6 text-white">Your teams</div>
              <ul role="list" class="-mx-2 mt-2 space-y-1">
                <li v-for="team in teams" :key="team.name">
                  <a
                    :href="team.href"
                    :class="[team.current ? 'bg-brand-dark text-white' : 'text-brand-light hover:text-white hover:bg-brand-dark', 'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold']"
                  >
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg border border-brand-light bg-brand-hover text-[0.625rem] font-medium text-white">
                      {{ team.initial }}
                    </span>
                    <span class="truncate">{{ team.name }}</span>
                  </a>
                </li>
              </ul>
            </li>
            <li class="-mx-6 mt-auto">
              <Menu as="div" class="w-100">
                <MenuButton class="flex w-100 items-center gap-x-4 px-6 py-3 text-sm font-semibold leading-6 text-white hover:bg-brand-dark">
                  <img class="h-8 w-8 rounded-full bg-brand-dark" :src="user.profile_photo_url" alt=""/>
                  <span class="sr-only" v-t="'components.layout.your_profile'"/>
                  <span aria-hidden="true">{{ user.name }}</span>
                </MenuButton>
                <transition
                  enter-active-class="transition ease-out duration-100"
                  enter-from-class="transform opacity-0 scale-95"
                  enter-to-class="transform opacity-100 scale-100"
                  leave-active-class="transition ease-in duration-75"
                  leave-from-class="transform opacity-100 scale-100"
                  leave-to-class="transform opacity-0 scale-95"
                >
                  <MenuItems class="absolute right-0 z-10 -translate-y-full -mt-2.5 w-32 origin-bottom-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none">
                    <MenuItem v-for="item in userNavigation" :key="item.name" v-slot="{ active }">
                      <Link
                        :href="$route(item.route)"
                        :class="[active ? 'bg-gray-50' : '', 'block px-3 py-1 text-sm leading-6 text-gray-900']"
                      >
                        {{ item.name }}
                      </Link>
                    </MenuItem>
                  </MenuItems>
                </transition>
              </Menu>
            </li>
          </ul>
        </nav>
      </div>
    </div>

    <div class="pb-4 lg:pl-72">
      <div class="py-4">
        <PortalTarget name="layout-header"/>
      </div>

      <main>
        <div class="px-4 sm:px-6 lg:px-8">
          <slot/>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import {
  Dialog,
  DialogPanel,
  Menu,
  MenuButton,
  MenuItem,
  MenuItems,
  TransitionChild,
  TransitionRoot,
} from '@headlessui/vue';
import { XMarkIcon } from '@heroicons/vue/24/outline';
import { appLogo, appName, layoutNavigationItems, userNavigationItems } from '../LayoutConfig';
import { Link, usePage } from '@inertiajs/vue3';
import { UserData } from '../Types/generated';
import SidebarNavEntry from './Partials/SidebarNavEntry.vue';
import { PortalTarget } from 'portal-vue';

const navigation = layoutNavigationItems;
const userNavigation = userNavigationItems;
const teams: { id: number, name: string, href: string, initial: string, current: boolean }[] = [];

const sidebarOpen = ref(false);

const user: UserData = usePage().props.user;
</script>
