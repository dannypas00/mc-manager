<template>
  <div class="absolute bottom-0 flex h-max w-full flex-col px-2">
    <div class="mb-1 flex items-center justify-between" v-if="useFrom">
      <label
        :for="`${filter.filter}-start`"
        v-t="'components.datatable.date_from_header.label'"
      />
      <div class="flex w-3/4 flex-row items-center">
        <ClearInputButton @click="() => (start = '')" />
        <DateInput
          v-model="start"
          :id="`${filter.filter}-start`"
          :min="internalValue.end"
        />
      </div>
    </div>
    <div class="mb-1 flex items-center justify-between" v-if="useUntil">
      <label
        :for="`${filter.filter}-end`"
        v-t="'components.datatable.date_until_header.label'"
      />
      <div class="flex w-3/4 flex-row items-center">
        <ClearInputButton @click="() => (end = '')" />
        <DateInput
          v-model="end"
          :id="`${filter.filter}-end`"
          :min="internalValue.start"
        />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts" generic="T extends Record<string, any>">
import {
  computed,
  ComputedRef,
  inject,
  PropType,
  ref,
  Ref,
  WritableComputedRef,
} from 'vue';
import {
  DateFilterOption,
  DateFilterType,
  DateRangeValue,
} from '../../DataTableTypes';
import DateInput from './DateInput.vue';
import ClearInputButton from '../ClearInputButton.vue';

const props = defineProps({
  filter: {
    type: Object as PropType<DateFilterOption<T>>,
    required: true,
  },
});

const useFrom: ComputedRef<boolean> = computed(() =>
  [DateFilterType.FromDate, DateFilterType.DateRange].includes(
    props.filter?.dateFilterType
  )
);
const useUntil: ComputedRef<boolean> = computed(() =>
  [DateFilterType.UntilDate, DateFilterType.DateRange].includes(
    props.filter?.dateFilterType
  )
);

const filterValuesMap: Ref<Record<string, Ref<unknown>>> | undefined =
  inject('filter-values');

const internalValue: Ref<DateRangeValue> = ref(
  (filterValuesMap?.value?.[props.filter.filter]?.value as DateRangeValue) ?? {}
);

const start: WritableComputedRef<string> = computed({
  get: () => internalValue.value.start ?? '',
  set: (value: string) => {
    internalValue.value.start = value;
    filterValue.value = internalValue.value;
  },
});

const end: WritableComputedRef<string> = computed({
  get: () => internalValue.value.end ?? '',
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
        `Cant assign value ${value} to filter ${props.filter.filter}`
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
