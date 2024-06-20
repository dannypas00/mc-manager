<template>
  <th
    scope="col"
    class="group relative px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
    :class="{ 'd-none': hideTitle }"
  >
    <span :class="{ 'group-hover:text-transparent': header.filter }">{{
      header.title
    }}</span>
    <div
      v-if="header.filter && filterComponent"
      class="absolute left-0 top-0 hidden h-full w-full group-hover:inline has-[:focus]:inline"
    >
      <Component :is="filterComponent" :filter="header.filter" />
    </div>
  </th>
</template>

<script setup lang="ts" generic="T extends Record<string, any>">
import { computed, PropType } from "vue";
import { FilterType, TableHeader } from "../DataTableTypes";
import DataTableSearchFilter from "./DataTableSearchFilter.vue";

const props = defineProps({
  header: {
    type: Object as PropType<TableHeader<T>>,
    required: true,
  },

  hideTitle: {
    type: Boolean,
    required: false,
    default: false,
  },
});

const filterComponent = computed(() => {
  if (!props.header.filter) {
    return undefined;
  }
  switch (props.header.filter.type) {
    case FilterType.Search:
      return DataTableSearchFilter;
    default:
      return undefined;
  }
});
</script>
