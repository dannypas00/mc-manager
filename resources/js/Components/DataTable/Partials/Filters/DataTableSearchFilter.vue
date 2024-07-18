<template>
  <div
    class="mx-1 mt-2 flex items-center rounded-md border-0 bg-white px-1 text-center text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 *:focus:ring-2 *:focus:ring-brand-light"
  >
    <FontAwesomeIcon
      :icon="{ prefix: 'fas', iconName: 'search' }"
      class="m-1 mx-1 aspect-square rounded-md p-1 text-center"
      aria-hidden="true"
    />

    <input
      type="text"
      v-model="filterValue"
      :name="filter.filter"
      :id="filter.filter"
      class="my-0.5 w-full border-0 px-0 py-1 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
      :class="{ 'has-input': filterValue?.length > 0 }"
      :placeholder="filter.placeholder"
    />

    <ClearInputButton @click="() => (filterValue = '')" />
  </div>
</template>

<script setup lang="ts" generic="T extends Record<string, any>">
import { computed, inject, PropType, ref, Ref, WritableComputedRef } from 'vue';
import { SearchFilterOption } from '../../DataTableTypes';
import ClearInputButton from '../ClearInputButton.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const props = defineProps({
  filter: {
    type: Object as PropType<SearchFilterOption>,
    required: true,
  },
});

const filterValuesMap: Ref<Record<string, Ref<unknown>>> | undefined =
  inject('filter-values');

const internalValue: Ref<string> = ref(
  (filterValuesMap?.value?.[props.filter.filter]?.value as string) ?? ''
);

const filterValue: WritableComputedRef<string> = computed({
  get: () => internalValue.value,
  set: (value: string) => {
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
