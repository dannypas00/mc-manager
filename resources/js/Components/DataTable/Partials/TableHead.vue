<template>
  <tr>
    <th v-if="selectable" scope="col" class="relative px-7 sm:w-12 sm:px-6">
      <input
        type="checkbox"
        class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
        :checked="indeterminate || selected.length === data.length"
        :indeterminate
        @change="
          selected = $event.target.checked ? data.map((p) => p[identifier]) : []
        "
      />
    </th>

    <DataTableHeader
      v-for="(header, index) in headers"
      :key="header.key"
      :hide-title="index === 0 && selected.length > 0"
      :header="header"
    />
  </tr>
</template>

<script setup lang="ts" generic="T extends Record<string, any>">
import DataTableHeader from "./DataTableHeader.vue";
import { computed, ComputedRef } from "vue";
import { TableHeader } from "../DataTableTypes";

const selected = defineModel("selected", {
  type: Array<T>,
  required: true,
});

const props = defineProps({
  headers: {
    type: Array<TableHeader<T>>,
    required: true,
  },

  data: {
    type: Array<T>,
    required: true,
  },

  identifier: {
    type: String,
    required: true,
  },

  selectable: {
    type: Boolean,
    required: false,
    default: false,
  },
});

const indeterminate: ComputedRef<boolean> = computed(
  () => selected.value.length > 0 && selected.value.length < props.data.length,
);
</script>
