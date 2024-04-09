<template>
  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ header.title }}</th>
</template>

<script setup lang="ts">
import { TableHeader } from './DataTableTypes';
import { PropType } from 'vue';

defineProps({
  header: {
    type: Object as PropType<TableHeader>,
    required: true,
  }
});
</script>
