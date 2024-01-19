<template>
  <Link
    :href="href"
    as="button"
    class="rounded-md bg-emerald-100 px-2.5 py-1.5 text-sm font-semibold text-slate-800 shadow-sm hover:bg-emerald-300 hover:text-slate-950 active:ring-2 active:ring-emerald-400"
  >
    <slot v-if="!!$slots.default"/>
    <span v-if="text">{{ text }}</span>
  </Link>
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
    require: false,
    default: '#',
  },
});
</script>
