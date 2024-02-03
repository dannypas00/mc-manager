<template>
  <Component
    :is="isLink ? Link : 'button'"
    class="inline-flex items-center gap-x-2 rounded-md px-2.5 py-1.5 text-sm font-semibold shadow-sm text-white bg-emerald-800 hover:bg-emerald-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-800 active:ring-2 active:ring-emerald-400"
    as="button"
    :href="href"
  >
    <slot v-if="!!$slots.default"/>
    <span v-if="text">{{ text }}</span>
  </Component>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import routeFn from 'ziggy-js';

defineProps({
  text: {
    type: String,
    required: false,
    default: '',
  },

  href: {
    type: [String, routeFn],
    required: false,
    default: '',
  },

  isLink: {
    type: Boolean,
    required: false,
    default: false,
  },
});
</script>
