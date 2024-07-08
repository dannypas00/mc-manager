<template>
  <nav
    class="flex items-center justify-between border-t border-gray-200 px-4 sm:px-0"
  >
    <div class="-mt-px flex w-0 flex-1">
      <a
        v-if="!isFirstPage"
        class="inline-flex cursor-pointer select-none items-center border-t-2 border-transparent pr-1 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 active:border-indigo-500 active:text-indigo-600"
        @click="prev"
      >
        <ArrowLongLeftIcon
          class="mr-3 h-5 w-5 text-gray-400"
          aria-hidden="true"
        />
        {{ $t('components.pagination.previous_button') }}
      </a>
    </div>
    <div class="hidden md:-mt-px md:flex">
      <template v-if="pageCount <= 9">
        <PaginationEntry
          v-for="page in Math.min(7, pageCount)"
          :index="page"
          :is-current="currentPage === page"
          :key="page"
        />
      </template>
      <template v-else>
        <template v-for="page in maxPageCount">
          <PaginationEntry
            v-if="offsetPage(page) > 0"
            :index="offsetPage(page)"
            :is-current="currentPage === offsetPage(page)"
            :key="offsetPage(page)"
            @click="currentPage = offsetPage(page)"
          />
        </template>
      </template>
    </div>
    <div class="-mt-px flex w-0 flex-1 justify-end">
      <a
        v-if="!isLastPage"
        class="inline-flex cursor-pointer select-none items-center border-t-2 border-transparent pl-1 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 active:border-indigo-500 active:text-indigo-600"
        @click="next"
      >
        {{ $t('components.pagination.next_button') }}
        <ArrowLongRightIcon
          class="ml-3 h-5 w-5 text-gray-400"
          aria-hidden="true"
        />
      </a>
    </div>
  </nav>
</template>

<script setup lang="ts">
import { computed, ComputedRef, ModelRef, PropType, watch } from 'vue';
import { ArrowLongLeftIcon, ArrowLongRightIcon } from '@heroicons/vue/20/solid';
import _ from 'lodash';
import PaginationEntry from './Partials/PaginationEntry.vue';
import { useOffsetPagination } from '@vueuse/core';
import { QueryBuilderIndexData } from '../../Communication/Base/QueryBuilderRequest';

// Recommended to be an odd number, so the current page is the middle number
const maxPageCount = 9;

const currentPageModel: ModelRef<number> = defineModel({
  type: Number,
  required: true,
});

const props = defineProps({
  data: {
    type: Object as PropType<QueryBuilderIndexData<never>>,
    required: true,
  },
});

const { currentPage, pageCount, isFirstPage, isLastPage, prev, next } =
  useOffsetPagination({
    total: props.data?.meta.total,
    page: props.data?.meta.current_page,
    pageSize: props.data?.meta.per_page,
    onPageChange({ currentPage }) {
      currentPageModel.value = currentPage;
    },
  });

watch(currentPageModel, newPage => {
  currentPage.value = newPage;
});

function offsetPage(page: number): number {
  return page - Math.ceil(maxPageCount / 2) + currentPage.value;
}
</script>
