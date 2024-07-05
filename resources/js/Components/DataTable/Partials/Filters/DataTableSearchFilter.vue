<template>
  <div class="relative mt-2 rounded-md shadow-sm">
    <input
      type="text"
      v-model="filterValue"
      :name="filter.filter"
      :id="filter.filter"
      class="block w-full rounded-md border-0 py-1.5 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-light sm:text-sm sm:leading-6"
      :class="{ 'has-input': filterValue?.length > 0 }"
      :placeholder="filter.placeholder"
    />

    <span
      class="absolute inset-y-0 right-0 m-1 mx-1 flex aspect-square items-center rounded-md p-1 text-center text-gray-900"
    >
      <MagnifyingGlassIcon aria-hidden="true" />
    </span>
  </div>
</template>

<script setup lang="ts" generic="T extends Record<string, any>">
import { MagnifyingGlassIcon } from "@heroicons/vue/16/solid";
import { computed, inject, PropType, ref, Ref, WritableComputedRef } from "vue";
import { SearchFilterOption } from "../../DataTableTypes";

const props = defineProps({
  filter: {
    type: Object as PropType<SearchFilterOption>,
    required: true,
  },
});

const filterValuesMap: Ref<Record<string, Ref<unknown>>> | undefined =
  inject("filter-values");

const internalValue: Ref<string> = ref(
  (filterValuesMap?.value?.[props.filter.filter]?.value as string) ?? "",
);

const filterValue: WritableComputedRef<string> = computed({
  get: () => internalValue.value,
  set: (value: string) => {
    internalValue.value = value;
    if (!filterValuesMap?.value) {
      console.debug(
        `Cant assign value ${value} to filter ${props.filter.filter}`,
      );
      return;
    }

    if (!filterValuesMap.value[props.filter.filter]?.value) {
      filterValuesMap.value[props.filter.filter] = ref(value);
      return;
    }

    filterValuesMap.value[props.filter.filter].value = value;
  },
});
</script>
