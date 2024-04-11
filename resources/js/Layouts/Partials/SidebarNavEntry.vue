<template>
  <li class="select-none">
    <Link
      :href="$route(item.route)"
      :class="[
        isCurrent
          ? 'bg-brand text-white'
          : 'text-brand-light hover:bg-brand hover:text-white',
        'group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6',
      ]"
    >
      <component
        :is="item.icon"
        :class="[
          isCurrent ? 'text-white' : 'text-brand-light group-hover:text-white',
          'h-6 w-6 shrink-0',
        ]"
        aria-hidden="true"
      />
      {{ item.name }}
    </Link>
  </li>
</template>

<script setup lang="ts">
import { NavigationItem } from "../../LayoutConfig";
import { Link, usePage } from "@inertiajs/vue3";
import { computed, ComputedRef } from "vue";

const props = defineProps<{
  item: NavigationItem;
}>();

const isCurrent: ComputedRef<boolean> = computed((): boolean => {
  // Invoke page url here because route().current() is not reactive
  if (usePage().url) {
    return route().current(props.item.route);
  }
  return false;
});
</script>
