<template>
  <a
    class="inline-flex cursor-pointer select-none items-center border-t-2 px-4 pt-4 text-sm font-medium active:border-indigo-500 active:text-indigo-600"
    :class="{
      'border-indigo-500 text-indigo-600': isCurrent,
      'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700':
        !isCurrent,
    }"
  >
    {{ index }}
  </a>
</template>

<script setup lang="ts">
defineProps({
  index: {
    type: Number,
    required: true,
  },
  isCurrent: {
    type: Boolean,
    required: false,
    default: false,
  },
});
</script>
