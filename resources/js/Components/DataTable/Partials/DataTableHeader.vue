<template>
  <th
    scope="col"
    class="group relative min-w-max py-3.5 pr-3 text-left text-sm font-semibold text-gray-900"
    :class="{ 'd-none': hideTitle }"
  >
    <span :class="{ 'group-hover:text-transparent': header.filter }">{{
      header.title
    }}</span>
    <div
      v-if="header.filter && filterComponent"
      class="absolute inset-0 hidden h-full w-full min-w-max group-hover:inline has-[.has-input]:inline has-[:focus]:inline"
    >
      <Component :is="filterComponent" :filter="header.filter" />
    </div>
  </th>
</template>

<script setup lang="ts" generic="T extends Record<string, any>">
import { computed, PropType } from "vue";
import { DateFilterType, FilterType, TableHeader } from "../DataTableTypes";
import DataTableSearchFilter from "./Filters/DataTableSearchFilter.vue";
import DataTableDateRangeFilter from "./Filters/DataTableDateRangeFilter.vue";

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
    case FilterType.Date:
      switch (props.header.filter.dateFilterType) {
        case DateFilterType.DateRange:
          return DataTableDateRangeFilter;
        default:
          return undefined;
      }
    default:
      return undefined;
  }
});
</script>
