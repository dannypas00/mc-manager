<template>
  <Link
    v-if="isLink"
    :href="href"
    class="inline-flex select-none items-center justify-center gap-x-2 rounded-md  px-3 py-1.5 font-semibold shadow-sm  focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-800 active:ring-2 active:ring-emerald-400"
    :class="outline ? 'border-2 border-emerald-800 text-emerald-800 hover:bg-emerald-50' : 'bg-emerald-800 text-white hover:bg-emerald-600'"
  >
    <slot v-if="!!$slots.default" />
    <span v-if="text">{{ text }}</span>
  </Link>
  <button
    v-else
    class="inline-flex select-none items-center justify-center gap-x-2 rounded-md  px-3 py-1.5 font-semibold shadow-sm  focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-800 active:ring-2 active:ring-emerald-400"
    :class="outline ? 'border-2 border-emerald-800 text-emerald-800 hover:bg-emerald-50' : 'bg-emerald-800 text-white hover:bg-emerald-600'"
  >
    <slot v-if="!!$slots.default" />
    <span v-if="text">{{ text }}</span>
  </button>
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

  outline: {
    type: Boolean,
    required: false,
    default: false,
  }
});
</script>
