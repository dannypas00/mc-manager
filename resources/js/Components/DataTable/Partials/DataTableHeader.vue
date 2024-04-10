<template>
  <th
    scope="col"
    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
    :class="{ 'd-none': hideTitle }"
  >
    {{ header.title }}
  </th>
</template>

<script setup lang="ts">
import { PropType } from 'vue';
import { TableHeader } from '../DataTableTypes';

defineProps({
  header: {
    type: Object as PropType<TableHeader>,
    required: true,
  },
  hideTitle: {
    type: Boolean,
    required: false,
    default: false,
  },
});
</script>
