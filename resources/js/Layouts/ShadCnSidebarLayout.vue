<template>
  <SidebarProvider :default-open>
    <Sidebar collapsible="icon">
      <SidebarHeader>
        <SidebarMenu>
          <SidebarMenuItem>
            <div class="flex items-center gap-2">
              <img :src="appLogo" class="size-8" />
              <span class="tracking-wider text-lg text-sidebar-primary font-bold truncate">{{ appName }}</span>
            </div>
          </SidebarMenuItem>
        </SidebarMenu>
      </SidebarHeader>
      <SidebarContent>
        <SidebarGroup>
          <SidebarGroupContent>
            <SidebarMenu>
              <SidebarMenuItem
                v-for="item in layoutNavigationItems"
                :key="item.name"
              >
                <SidebarMenuButton asChild>
                  <Link as="a" :href="$route(item.route)">
                    <FontAwesomeIcon :icon="item.icon" />
                    <span>{{ item.name }}</span>
                  </Link>
                </SidebarMenuButton>
              </SidebarMenuItem>
            </SidebarMenu>
          </SidebarGroupContent>
        </SidebarGroup>
      </SidebarContent>
      <SidebarFooter>
        <SidebarMenu>
          <SidebarMenuItem>
            <DropdownMenu v-model:open="userDropupOpen">
              <DropdownMenuTrigger asChild>
                <SidebarMenuButton>
                  <img
                    class="size-6 rounded-full"
                    :src="$page.props.user.profile_photo_url"
                  />
                  <span
                    v-t="'components.layout.your_profile'"
                    class="sr-only"
                  />
                  <span aria-hidden="true" class="truncate">{{ $page.props.user.name }}</span>
                  <FontAwesomeIcon
                    icon="chevron-right"
                    class="ml-auto text-slate-500 transition-all"
                    :rotation="userDropupOpen ? 180 : undefined"
                  />
                </SidebarMenuButton>
              </DropdownMenuTrigger>
              <DropdownMenuContent
                side="right"
                class="mb-2 ml-3 w-[--reka-popper-anchor-width] text-slate-700"
              >
                <Link
                  v-for="navigation in userNavigationItems"
                  :href="$route(navigation.route)"
                  as="a"
                >
                  <DropdownMenuItem
                    class="cursor-pointer"
                    :active="$route().current(navigation.route)"
                  >
                    <FontAwesomeIcon :icon="navigation.icon" />
                    {{ navigation.name }}
                  </DropdownMenuItem>
                </Link>
              </DropdownMenuContent>
            </DropdownMenu>
          </SidebarMenuItem>
        </SidebarMenu>
      </SidebarFooter>
    </Sidebar>

    <main>
      <SidebarInset>
        <SidebarTrigger />
        <slot />
      </SidebarInset>
    </main>
  </SidebarProvider>
</template>

<script setup lang="ts">
import {
  Sidebar,
  SidebarContent,
  SidebarFooter,
  SidebarGroup,
  SidebarGroupContent,
  SidebarGroupLabel,
  SidebarHeader,
  SidebarInset,
  SidebarMenu,
  SidebarMenuAction,
  SidebarMenuButton,
  SidebarMenuItem,
  SidebarProvider,
  SidebarTrigger,
} from '@/components/ui/sidebar';
import {
  appLogo,
  appName,
  layoutNavigationItems,
  userNavigationItems,
} from '@/LayoutConfig';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useLocalStorage } from '@vueuse/core';
import { useCookies } from '@vueuse/integrations';
import { SIDEBAR_COOKIE_NAME } from '@/components/ui/sidebar/utils';

const userDropupOpen = ref(false);
const cookies = useCookies([SIDEBAR_COOKIE_NAME]);
const defaultOpen = cookies.get(SIDEBAR_COOKIE_NAME);
</script>
