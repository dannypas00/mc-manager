<template>
  <Portal to="main-layout-header">
    <div
      class="mx-auto flex h-16 items-center justify-between px-4 py-4 sm:px-6 lg:px-8"
    >
      <h1
        class="inline-block align-middle text-lg font-semibold text-slate-900"
      >
        {{ props.title }}
      </h1>

      <slot />
    </div>
  </Portal>
</template>

<script setup>
const props = defineProps({
  title: {
    type: String,
    required: true,
  },

  // Defaults to title
  header: {
    type: String,
    required: false,
    default: null,
  },

  // TODO: Add breadcrumbs
});

document.title =
  (props.header ?? props.title) + ' - ' + import.meta.env.VITE_APP_NAME;
</script>
