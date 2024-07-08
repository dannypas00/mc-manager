<template>
  <DataTable
    v-bind="{
      headers,
      identifier,
      selectable,
      rowClickable,
      bulkActions,
    }"
    v-model:selected="selected"
    :data="data.data"
  />

  <WidePagination v-model="currentPage" :last-page="data.meta.last_page" />
</template>

<script setup lang="ts" generic="T extends Record<string, unknown>">
import DataTable from './DataTable.vue';
import { QueryBuilderIndexRequest } from '../../Communication/Base/QueryBuilderIndexRequest';
import { BulkOption, TableHeader } from './DataTableTypes';
import {
  computed,
  ModelRef,
  onMounted,
  PropType,
  provide,
  Ref,
  ref,
  unref,
  watch,
} from 'vue';
import { AxiosResponse } from 'axios';
import { QueryBuilderIndexData } from '../../Communication/Base/QueryBuilderRequest';
import { useDebounceFn, watchDeep } from '@vueuse/core';
import WidePagination from '../Pagination/WidePagination.vue';
import _ from 'lodash';
import { SortDirection } from '../../Utilities/SortDirection';

const emit = defineEmits(['update:selected']);

const selectedModel: ModelRef<Array<T>> = defineModel('selected', {
  type: Array<T>,
  required: false,
  default: undefined,
});

const selected = computed({
  get: () => selectedModel.value,
  set: value => emit('update:selected', value),
});

const props = defineProps({
  headers: {
    type: Array as PropType<TableHeader<T>[]>,
    required: true,
  },

  identifier: {
    type: String,
    required: false,
    default: 'id',
  },

  selectable: {
    type: Boolean,
    required: false,
    default: false,
  },

  rowClickable: {
    type: Boolean,
    required: false,
    default: false,
  },

  bulkActions: {
    type: Array<BulkOption<T>>,
    required: false,
    default: () => [],
  },

  request: {
    type: QueryBuilderIndexRequest<T>,
    required: true,
  },

  autoSearch: {
    type: Boolean,
    required: false,
    default: false,
  },

  requestDebounceMs: {
    type: Number,
    required: false,
    default: 300,
  },
});

const data: Ref<QueryBuilderIndexData<T>> = ref({
  data: [],
  links: [],
  meta: {
    current_page: -1,
    last_page: -1,
    from: -1,
    per_page: -1,
    to: -1,
    total: -1,
  },
});

const currentPage: Ref<number> = ref(1);

const requestData = useDebounceFn(getData, props.requestDebounceMs);

// Generate object with filters as keys and undefined as value to initialize filter values map
const filterValues: Ref<Record<string, Ref<unknown>>> = ref(
  _(props.headers)
    .map('filter')
    .filter()
    .keyBy('filter')
    .mapValues(() => ref(undefined))
    .value()
);
provide('filter-values', filterValues);

const sortValues = ref(
  _(props.headers)
    .filter('sortable')
    .keyBy('key')
    .mapValues((header: TableHeader<T>) =>
      ref(header.defaultSortDirection ?? SortDirection.None)
    )
    .value()
);
provide('sort-values', sortValues);

watch(currentPage, requestData);
if (props.autoSearch) {
  watchDeep(filterValues, requestData);
  watchDeep(sortValues, requestData);
}

onMounted(() => {
  getData();
});

async function getData() {
  props.request
    .setFilters(_.mapValues(filterValues.value, unref))
    .setSort(_.mapValues(sortValues.value, unref))
    .setPage(currentPage.value)
    .getResponse()
    .then((response: AxiosResponse) => {
      data.value = response.data;
    });
}

// Explicitly expose the getData function so that parent components can refresh the data
defineExpose({ getData });
</script>
