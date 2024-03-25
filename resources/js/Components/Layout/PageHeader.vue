<template>
  <Portal to="layout-header">
    <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
      <h1 class="text-lg font-semibold leading-6 text-gray-900">{{ header }}</h1>
    </div>
  </Portal>
</template>

<script setup lang="ts">
const props = defineProps<{
  /**
   * Header used in navbar
   */
  header: {
    type: string,
    required: true,
  },

  /**
   * Title of the page, used in document / tab title
   */
  title: {
    type: string,
    required: false,
    default: null,
  },
}>();

document.title = (props.title ?? props.header) + ' - ' + import.meta.env.VITE_APP_NAME;
</script>
