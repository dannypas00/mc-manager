<template>
  <Portal to="loader">
    <div class=" bg-gray-300/70 w-full h-full z-[60] inset-0 fixed flex items-center justify-center">
      <div class="flex flex-col font-bold text-center space-y-4">
        <FontAwesomeIcon icon="fas fa-spinner" spin-pulse size="4x"/>
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
  }
})
</script>
