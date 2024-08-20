<template>
  <Component
    :is="isLink ? Link : 'button'"
    :href="href"
    as="button"
    class="select-none inline-flex justify-center items-center gap-x-2 rounded-md bg-emerald-800 px-3 py-1.5 font-semibold text-white shadow-sm hover:bg-emerald-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-800 active:ring-2 active:ring-emerald-400"
  >
    <slot v-if="!!$slots.default" />
    <span v-if="text">{{ text }}</span>
  </Component>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps({
  text: {
    type: String,
    required: false,
    default: '',
  },

  href: {
    type: String,
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
