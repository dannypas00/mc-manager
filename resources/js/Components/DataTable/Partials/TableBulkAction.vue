<template>
  <button
    type="button"
    class="inline-flex items-center rounded bg-white px-2 py-1 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-30 disabled:hover:bg-white"
    @click="onActionClick"
  >
    {{ action.title }}
  </button>
</template>

<script setup lang="ts" generic="T extends Record<string, any>">
import { PropType } from 'vue';
import { BulkOption } from '../DataTableTypes';

const selected = defineModel('selected', {
  type: Array<T>,
  required: true,
});

const props = defineProps({
  action: {
    type: Object as PropType<BulkOption<T>>,
    required: true,
  },
});

function onActionClick () {
  if (props.action?.unselectAfter) {
    selected.value = [];
  }

  if (props.action?.onClick) {
    props.action?.onClick(selected.value);
  }
}
</script>
