<template>
  <div class="space-y-10 divide-y divide-gray-900/10">
    <div
      v-for="segment in segments"
      :key="segment.slotName"
      class="grid grid-cols-1 gap-x-8 gap-y-8 md:grid-cols-3"
    >
      <div class="px-4 sm:px-0">
        <h2 class="text-base font-semibold leading-7 text-gray-900">
          {{ segment.title }}
        </h2>
        <p class="mt-1 text-sm leading-6 text-gray-600">
          {{ segment.description }}
        </p>
      </div>

      <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
        <div class="px-4 py-6 sm:p-8">
          <slot :name="`segment-${segment.slotName}`"/>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">

import { PropType } from 'vue';
import { PanelSegment } from '../../Types/Components/PanelSegment';

defineProps({
  segments: {
    type: Array as PropType<PanelSegment[]>,
    required: true,
  },
});
</script>
