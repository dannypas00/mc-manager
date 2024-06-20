<template>
  <div class="relative mt-2 rounded-md shadow-sm">
    <input
      type="text"
      v-model="filterValue"
      :name="filter.filter"
      :id="filter.filter"
      class="block w-full rounded-md border-0 py-1.5 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-light sm:text-sm sm:leading-6"
      :placeholder="filter.placeholder"
      @keydown="
        $event.key === 'Enter' && $emit('submit', { filter, filterValue })
      "
    />

    <button
      class="absolute inset-y-0 right-0 m-1 mx-1 flex aspect-square cursor-pointer items-center rounded-md p-1 text-center text-gray-900 hover:text-gray-600 hover:ring-2 hover:ring-gray-900 active:ring-2 active:ring-brand-light active:ring-offset-brand-light"
      @click="$emit('submit', { filter, filterValue })"
    >
      <MagnifyingGlassIcon aria-hidden="true" />
    </button>
  </div>
</template>

<script setup lang="ts" generic="T extends Record<string, any>">
import { MagnifyingGlassIcon } from "@heroicons/vue/16/solid";
import { PropType, ref } from "vue";
import { SearchFilterOption } from "../DataTableTypes";

defineEmits(["submit"]);

defineProps({
  filter: {
    type: Object as PropType<SearchFilterOption>,
    required: true,
  },
});

const filterValue = ref();
</script>
