<template>
  <Link
    class="inline-flex items-center gap-x-2 rounded-md px-3.5 py-2.5 text-sm font-semibold shadow-sm text-white bg-emerald-800 hover:bg-emerald-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-800"
    as="button"
    :href="href"
  >
    <slot v-if="!!$slots.default"/>
    {{ text }}
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
