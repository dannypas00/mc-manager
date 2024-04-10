<template>
  <div class="px-4 sm:px-6 lg:px-8">
    <DataTable
      v-bind="{
          headers,
          identifier,
          selectable,
          rowClickable,
          bulkOptions,
        }"
      :data="data.data"
    />

    <nav class="flex items-center justify-between border-t border-gray-200 px-4 sm:px-0">
      <div class="-mt-px flex w-0 flex-1">
        <a
          v-if="currentPage > 1"
          class="inline-flex items-center border-t-2 border-transparent pr-1 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 cursor-pointer select-none active:border-indigo-500 active:text-indigo-600"
          @click="previousPage"
        >
          <ArrowLongLeftIcon class="mr-3 h-5 w-5 text-gray-400" aria-hidden="true"/>
          Previous
        </a>
      </div>
      <div class="hidden md:-mt-px md:flex">
        <template v-if="leftPages[0] !== 1 && currentPage !== 1">
          <!-- Start -->
          <PaginationEntry
            :index="1"
            @click="() => setPage(1)"
          />
        </template>

        <!-- Left numbers -->
        <PaginationEntry
          v-for="index in leftPages ?? []"
          :index="index"
          @click="() => setPage(index)"
        />

        <!-- Current page -->
        <PaginationEntry
          :index="currentPage"
          is-current
          @click="requestData"
        />

        <!-- Right numbers -->
        <PaginationEntry
          v-for="index in rightPages ?? []"
          :index="index"
          @click="() => setPage(index)"
        />

        <template v-if="data?.meta?.last_page && rightPages[rightPages.length - 1] !== data.meta.last_page && currentPage !== data.meta.last_page">
          <!-- End -->
          <PaginationEntry
            :index="data.meta.last_page"
            @click="() => setPage(data.meta.last_page)"
          />
        </template>
      </div>
      <div class="-mt-px flex w-0 flex-1 justify-end">
        <a
          v-if="currentPage < data?.meta?.last_page"
          class="inline-flex items-center border-t-2 border-transparent pl-1 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 cursor-pointer select-none active:border-indigo-500 active:text-indigo-600"
          @click="nextPage"
        >
          Next
          <ArrowLongRightIcon class="ml-3 h-5 w-5 text-gray-400" aria-hidden="true"/>
        </a>
      </div>
    </nav>
  </div>
</template>

<script setup lang="ts" generic="T extends Record<string, any>">
import { PropType } from 'vue/dist/vue';
import DataTable from './DataTable.vue';
import { QueryBuilderIndexRequest } from '../../Communication/Base/QueryBuilderIndexRequest';
import { BulkOption, TableHeader } from './DataTableTypes';
import { computed, ComputedRef, onMounted, Ref, ref, watch } from 'vue';
import { AxiosResponse } from 'axios';
import { QueryBuilderIndexData } from '../../Communication/Base/QueryBuilderRequest';
import { ArrowLongLeftIcon, ArrowLongRightIcon } from '@heroicons/vue/20/solid';
import { useDebounce, useDebounceFn } from '@vueuse/core';
import { range } from 'lodash';
import PaginationEntry from './PaginationEntry.vue';

const paginationLimitLeft = 3;
const paginationLimitRight = 3;

const props = defineProps({
  headers: {
    type: Array as PropType<TableHeader[]>,
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

  bulkOptions: {
    type: Array as PropType<BulkOption<T>[]>,
    required: false,
    default: [],
  },

  request: {
    type: Object as PropType<QueryBuilderIndexRequest<T>>,
    required: true,
  },
});

const data: Ref<QueryBuilderIndexData<T[]>> = ref({
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

const startPages: ComputedRef<number[]> = computed(() => {
  return range(1, Math.max(paginationLimitLeft, data.value.meta.last_page));
});

const endPages: ComputedRef<number[]> = computed(() => {
  return range(Math.min(paginationLimitRight));
});

const currentIsInStart: ComputedRef<boolean> = computed(() => startPages.value.includes(currentPage.value));
const currentIsInEnd: ComputedRef<boolean> = computed(() => endPages.value.includes(currentPage.value));

const leftPages: ComputedRef<number[]> = computed(() => range(
  Math.max(1, currentPage.value - paginationLimitLeft),
  currentPage.value,
).filter(page => page !== currentPage.value));

const rightPages: ComputedRef<number[]> = computed(() => range(
  currentPage.value + 1,
  data.value.meta.last_page + 1,
).filter(page => page !== currentPage.value));

const requestData = useDebounceFn(getData);

function getData () {
  console.log('Getting data');
  // props.request.cancel('Changing filters during request');
  props.request
    .setPage(currentPage.value)
    .getResponse()
    .then((response: AxiosResponse) => {
      data.value = response.data;
    });
}

function nextPage () {
  setPage(currentPage.value + 1);
}

function previousPage () {
  setPage(currentPage.value - 1);
}

function setPage (page: number) {
  currentPage.value = Math.min(page, data.value.meta.last_page);
}

watch(currentPage, requestData);

onMounted(getData);
</script>
