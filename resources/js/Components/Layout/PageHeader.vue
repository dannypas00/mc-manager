<template>
  <Portal to="layout-header">
    <div class="flex w-full justify-between px-4 sm:px-6 lg:px-8">
      <div class="grow">
        <h1 class="text-lg font-semibold leading-6 text-gray-900">
          {{ header }}
        </h1>
      </div>

      <div class="grow-0">
        <slot />
      </div>
    </div>
  </Portal>
</template>

<script setup lang="ts">
const props = defineProps({
  /**
   * Header used in navbar
   */
  header: {
    type: String,
    required: true,
  },

  /**
   * Title of the page, used in document / tab title
   */
  title: {
    type: String,
    required: false,
    default: null,
  },
});

document.title =
  (props.title ?? props.header) + " - " + import.meta.env.VITE_APP_NAME;
</script>
