<template>
  <Link
    :href="$route(item.route)"
    as="a"
  >
    <DisclosureButton
      as="a"
      :class="[isCurrent ? 'bg-brand-dark text-white' : 'text-white hover:bg-brand-hover hover:bg-opacity-75', 'block rounded-md px-3 py-2 text-base font-medium cursor-pointer select-none']"
      :aria-current="isCurrent ? 'page' : undefined"
      @click="$emit('select')"
    >
      {{ item.name }}
    </DisclosureButton>
  </Link>
</template>

<script setup lang="ts">
import { NavigationItem } from '../../LayoutConfig';
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ComputedRef } from 'vue';
import { DisclosureButton } from '@headlessui/vue';

const props = defineProps<{
  item: NavigationItem,
}>();

const isCurrent: ComputedRef<boolean> = computed((): boolean => {
  // Invoke page url here because route().current() is not reactive
  if (usePage().url) {
    return route().current(props.item.route);
  }
  return false;
});

</script>
