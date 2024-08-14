<template>
  <Component
    :is="isLink ? Link : 'button'"
    :href="href"
    as="button"
    class="rounded-md bg-emerald-100 px-2.5 py-1.5 text-sm font-semibold text-slate-800 shadow-sm hover:bg-emerald-300 hover:text-slate-950 active:ring-2 active:ring-emerald-400"
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
    require: false,
    default: '',
  },

  isLink: {
    type: Boolean,
    required: false,
    default: false,
  },
});
</script>
