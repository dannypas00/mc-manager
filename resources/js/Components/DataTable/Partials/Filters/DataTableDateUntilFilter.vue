<template>
  <div class="absolute bottom-0 flex h-max w-full flex-col px-2">
    <div class="flex items-center justify-between">
      <label
        :for="filter.filter"
        v-t="'components.datatable.date_until_header.label'"
      />
      <input
        v-model="end"
        :id="filter.filter"
        type="date"
        class="w-3/4 rounded-sm bg-none text-gray-400"
        :class="{ 'has-input': internalValue.end }"
      />
    </div>
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

const internalValue: Ref<DateRangeValue> = ref(
  (filterValuesMap?.value?.[props.filter.filter]?.value as DateRangeValue) ??
  {},
);

const end: WritableComputedRef<string> = computed({
  get: () => internalValue.value.start ?? "",
  set: (value: string) => {
    internalValue.value.end = value;
    filterValue.value = internalValue.value;
  },
});

const filterValue: WritableComputedRef<DateRangeValue> = computed({
  get: () => internalValue.value,
  set: (value: DateRangeValue) => {
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
