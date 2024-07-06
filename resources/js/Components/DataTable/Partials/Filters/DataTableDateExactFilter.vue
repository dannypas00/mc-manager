<template>
  <div class="flex items-center justify-between">
    <label
      :for="filter.filter"
      v-t="'components.datatable.date_exact_header.label'"
    />
    <input
      v-model="filterValue"
      :id="filter.filter"
      type="date"
      class="w-3/4 rounded-sm bg-none text-gray-400"
      :class="{ 'has-input': filterValue > 0 }"
    />
  </div>
</template>

<script setup lang="ts" generic="T extends Record<string, any>">
import { computed, inject, PropType, ref, Ref, WritableComputedRef } from "vue";
import {
  DateFilterOption,
  DateRangeValue,
  SearchFilterOption,
} from "../../DataTableTypes";

const props = defineProps({
  filter: {
    type: Object as PropType<DateFilterOption<T>>,
    required: true,
  },
});

const filterValuesMap: Ref<Record<string, Ref<unknown>>> | undefined =
  inject("filter-values");

const internalValue: Ref<Date | null> = ref(
  (filterValuesMap?.value?.[props.filter.filter]?.value as Date) ?? null,
);

const filterValue: WritableComputedRef<Date | null> = computed({
  get: () => internalValue.value,
  set: (value: Date | null) => {
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
