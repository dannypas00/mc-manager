<template>
  <Portal to="main-layout-header">
    <div class="mx-auto px-4 py-4 h-16 flex justify-between items-center sm:px-6 lg:px-8">
      <h1 class="text-lg font-semibold text-slate-900 align-middle inline-block">
        {{ props.title }}
      </h1>

      <slot/>
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

document.title = (props.header ?? props.title) + ' - ' + import.meta.env.VITE_APP_NAME;
</script>
