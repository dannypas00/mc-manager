<template>
  <Portal to="loader">
    <div
      class="fixed inset-0 z-[60] flex h-full w-full items-center justify-center bg-gray-300/70"
    >
      <div class="flex select-none flex-col space-y-4 text-center font-bold">
        <FontAwesomeIcon icon="fas fa-spinner" spin-pulse size="4x" />
        <span v-if="reason">{{ reason }}</span>
      </div>
    </div>
  </Portal>
</template>

<script setup lang="ts">
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

defineProps({
  reason: {
    type: String,
    required: false,
    default: undefined,
  },
});
</script>
