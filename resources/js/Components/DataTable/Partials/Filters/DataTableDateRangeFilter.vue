<template>
  <div class="absolute inset-0 grid grid-flow-row align-bottom gap-6 min-w-max p-1">
    <input
      v-model="start"
      type="date"
      class="rounded-md text-gray-900 h-fit bg-none p-1"
      :class="{ 'has-input': internalValue.start }"
      :max="internalValue.end"
      placeholder="From"
    />
    <input
      v-model="end"
      type="date"
      class="rounded-md text-gray-900 h-fit bg-none p-1"
      :class="{ 'has-input': internalValue.end }"
      :min="internalValue.start"
      placeholder="To"
    />
  </div>
</template>

<script setup lang="ts">
import { computed, inject, PropType, ref, Ref, WritableComputedRef } from "vue";
import { DateRangeValue, SearchFilterOption } from "../../DataTableTypes";

const props = defineProps({
  filter: {
    type: Object as PropType<SearchFilterOption>,
    required: true,
  },
});

const filterValuesMap: Ref<Record<string, Ref<unknown>>> | undefined =
  inject("filter-values");

const internalValue: Ref<DateRangeValue> = ref(
  (filterValuesMap?.value?.[props.filter.filter]?.value as DateRangeValue) ??
    {},
);

const start: WritableComputedRef<string> = computed({
  get: () => internalValue.value.start ?? "",
  set: (value: string) => {
    internalValue.value.start = value;
    filterValue.value = internalValue.value;
  },
});

const end: WritableComputedRef<string> = computed({
  get: () => internalValue.value.end ?? "",
  set: (value: string) => {
    console.log(value);
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
      console.log(filterValuesMap.value[props.filter.filter], "a", value);
      return;
    }

    filterValuesMap.value[props.filter.filter].value = value;
    console.log(filterValuesMap.value[props.filter.filter].value, "b");
  },
});
</script>
