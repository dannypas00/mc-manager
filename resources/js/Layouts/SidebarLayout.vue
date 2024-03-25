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
              <a
                href="#"
                class="flex items-center gap-x-4 px-6 py-3 text-sm font-semibold leading-6 text-white hover:bg-brand-dark"
              >
                <img class="h-8 w-8 rounded-full bg-brand-dark" :src="user.profile_photo_url" alt=""/>
                <span class="sr-only">Your profile</span>
                <span aria-hidden="true">{{ user.name }}</span>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </div>

    <div class="sticky top-0 z-40 flex items-center gap-x-6 bg-brand-hover px-4 py-4 shadow-sm sm:px-6 lg:hidden">
      <button type="button" class="-m-2.5 p-2.5 text-brand-light lg:hidden" @click="sidebarOpen = true">
        <span class="sr-only">Open sidebar</span>
        <Bars3Icon class="h-6 w-6" aria-hidden="true"/>
      </button>
      <div class="flex-1 text-sm font-semibold leading-6 text-white">Dashboard</div>
      <a href="#">
        <span class="sr-only">{{ user.name }}</span>
        <img class="h-8 w-8 rounded-full bg-brand-dark" :src="user.profile_photo_url" alt=""/>
      </a>
    </div>

    <main class="py-10 lg:pl-72">
      <div class="px-4 sm:px-6 lg:px-8">
        <slot/>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { Bars3Icon, XMarkIcon } from '@heroicons/vue/24/outline';
import { appLogo, appName, layoutNavigationItems } from '../LayoutConfig';
import { usePage } from '@inertiajs/vue3';
import { UserData } from '../Types/generated';
import SidebarNavEntry from './Partials/SidebarNavEntry.vue';

const navigation = layoutNavigationItems;
const teams: { id: number, name: string, href: string, initial: string, current: boolean }[] = [];

const sidebarOpen = ref(false);

const page = usePage();
const user: UserData = page.props.user;
</script>
