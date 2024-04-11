<template>
  <nav
    class="flex items-center justify-between border-t border-gray-200 px-4 sm:px-0"
  >
    <div class="-mt-px flex w-0 flex-1">
      <a
        v-if="currentPage > 1"
        class="inline-flex cursor-pointer select-none items-center border-t-2 border-transparent pr-1 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 active:border-indigo-500 active:text-indigo-600"
        @click="previousPage"
      >
        <ArrowLongLeftIcon
          class="mr-3 h-5 w-5 text-gray-400"
          aria-hidden="true"
        />
        {{ $t("components.pagination.previous_button") }}
      </a>
    </div>
    <div class="hidden md:-mt-px md:flex">
      <template v-if="leftPages[0] !== 1 && currentPage !== 1">
        <!-- Start -->
        <PaginationEntry :index="1" @click="() => setPage(1)" />
      </template>

      <!-- Left numbers -->
      <PaginationEntry
        v-for="index in leftPages ?? []"
        :key="index"
        :index="index"
        @click="() => setPage(index)"
      />

      <!-- Current page -->
      <PaginationEntry :index="currentPage" is-current />

      <!-- Right numbers -->
      <PaginationEntry
        v-for="index in rightPages ?? []"
        :key="index"
        :index="index"
        @click="() => setPage(index)"
      />

      <template
        v-if="
          rightPages[rightPages.length - 1] !== lastPage &&
          currentPage !== lastPage
        "
      >
        <!-- End -->
        <PaginationEntry :index="lastPage" @click="() => setPage(lastPage)" />
      </template>
    </div>
    <div class="-mt-px flex w-0 flex-1 justify-end">
      <a
        v-if="currentPage < lastPage"
        class="inline-flex cursor-pointer select-none items-center border-t-2 border-transparent pl-1 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 active:border-indigo-500 active:text-indigo-600"
        @click="nextPage"
      >
        {{ $t("components.pagination.next_button") }}
        <ArrowLongRightIcon
          class="ml-3 h-5 w-5 text-gray-400"
          aria-hidden="true"
        />
      </a>
    </div>
  </nav>
</template>

<script setup lang="ts">
import { computed, ComputedRef, ModelRef } from "vue";
import { ArrowLongLeftIcon, ArrowLongRightIcon } from "@heroicons/vue/20/solid";
import _ from "lodash";
import PaginationEntry from "./Partials/PaginationEntry.vue";

const currentPage: ModelRef<number> = defineModel({
  type: Number,
  required: true,
});

const props = defineProps({
  paginationLimitLeft: {
    type: Number,
    required: false,
    default: 3,
  },
  paginationLimitRight: {
    type: Number,
    required: false,
    default: 3,
  },
  lastPage: {
    type: Number,
    required: true,
  },
});

const leftPages: ComputedRef<number[]> = computed(() =>
  _.range(
    Math.max(1, currentPage.value - props.paginationLimitLeft),
    currentPage.value,
  ).filter((page: number) => page !== currentPage.value),
);

const rightPages: ComputedRef<number[]> = computed(() =>
  _.range(currentPage.value + 1, props.lastPage + 1).filter(
    (page: number) => page !== currentPage.value,
  ),
);

function nextPage() {
  setPage(currentPage.value + 1);
}

function previousPage() {
  setPage(currentPage.value - 1);
}

function setPage(page: number) {
  currentPage.value = Math.min(page, props.lastPage);
}
</script>
