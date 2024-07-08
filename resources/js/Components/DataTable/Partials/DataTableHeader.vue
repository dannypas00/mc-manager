<template>
  <th
    scope="col"
    class="min-w-max text-left text-sm font-semibold text-gray-900"
    :class="{ 'd-none': hideTitle }"
  >
    <div class="flex flex-row items-center gap-2 w-full">
      <FontAwesomeIcon
        :icon="{
          prefix: 'fas',
          iconName: 'arrow-up',
        }"
        :rotation="sortDirection.direction === 'asc' ? 180 : undefined"
        class="cursor-pointer"
        :class="{ 'text-brand-light': sortDirection.direction !== null }"
        @click="() => (sortDirection.direction = sortDirection.next())"
      />
      <div class="group relative h-full py-3.5 pr-3 grow">
        <span
          :class="{
            'group-hover:text-transparent group-has-[.has-input]:text-transparent group-has-[:focus]:text-transparent':
              header.filter,
          }"
        >
          {{ header.title }}
        </span>
        <div
          v-if="header.filter && filterComponent"
          class="absolute inset-0 hidden h-full w-full min-w-max select-none group-hover:select-auto has-[.has-input]:select-auto has-[:focus]:select-auto group-hover:inline has-[.has-input]:inline has-[:focus]:inline"
        >
          <Component :is="filterComponent" :filter="header.filter" />
        </div>
      </div>
    </div>
  </th>
</template>

<script setup lang="ts" generic="T extends Record<string, any>">
import { computed, PropType, ref } from 'vue';
import {
  DateFilterType,
  FilterType,
  SortDirection,
  TableHeader,
} from '../DataTableTypes';
import DataTableSearchFilter from './Filters/DataTableSearchFilter.vue';
import DataTableDateRangeFilter from './Filters/DataTableDateRangeFilter.vue';
import DataTableDateFromFilter from './Filters/DataTableDateFromFilter.vue';
import DataTableDateUntilFilter from './Filters/DataTableDateUntilFilter.vue';
import DataTableDateExactFilter from './Filters/DataTableDateExactFilter.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

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

const sortDirection = ref(new SortDirection());

const filterComponent = computed(() => {
  if (!props.header.filter) {
    return undefined;
  }
  switch (props.header.filter.type) {
    case FilterType.Search:
      return DataTableSearchFilter;
    case FilterType.Date:
      switch (props.header.filter.dateFilterType) {
        // These use the prop type to disable one or the other component
        case DateFilterType.FromDate:
        case DateFilterType.UntilDate:
        case DateFilterType.DateRange:
          return DataTableDateRangeFilter;
        case DateFilterType.ExactDate:
          return DataTableDateExactFilter;
        default:
          return undefined;
      }
    default:
      return undefined;
  }
});
</script>
