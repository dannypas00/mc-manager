<template>
  <a
    class="inline-flex items-center border-t-2 px-4 pt-4 text-sm font-medium cursor-pointer select-none active:border-indigo-500 active:text-indigo-600"
    :class='{
      "border-indigo-500 text-indigo-600": isCurrent,
      "border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300": !isCurrent
    }'
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
