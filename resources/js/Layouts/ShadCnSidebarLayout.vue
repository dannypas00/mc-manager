<template>
  <SidebarProvider>
    <Sidebar>
      <SidebarHeader>
        <div class="flex items-center gap-2">
          <img :src="appLogo" class="size-8" />
          <span class="tracking-xl text-lg font-bold">{{ appName }}</span>
        </div>
      </SidebarHeader>
      <SidebarContent>
        <SidebarGroup>
          <SidebarGroupLabel>User</SidebarGroupLabel>
          <SidebarGroupContent>
            <SidebarMenu>
              <SidebarMenuItem
                v-for="item in layoutNavigationItems"
                :key="item.name"
              >
                <SidebarMenuButton asChild>
                  <a :href="$route(item.route)">
                    <FontAwesomeIcon :icon="item.icon" />
                    <span>{{ item.name }}</span>
                  </a>
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
                  <span aria-hidden="true">{{ $page.props.user.name }}</span>
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
      <SidebarTrigger />
      <slot />
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

const userDropupOpen = ref(false);
</script>
