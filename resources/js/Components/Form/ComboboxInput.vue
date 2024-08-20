<template>
  <Combobox as="div" v-model="selected" @update:modelValue="query = ''">
    <ComboboxLabel
      class="block text-sm font-medium leading-6 text-gray-900"
      v-if="label"
    >
      {{ label }}
      <span v-if="required" class="text-red-400">*</span>
    </ComboboxLabel>
    <div class="relative mt-2">
      <ComboboxInput
        class="w-full rounded-md border-0 bg-white py-1.5 pl-3 pr-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
        @change="query = $event.target.value"
        @blur="query = ''"
      />
      <ComboboxButton
        class="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none"
      >
        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
      </ComboboxButton>

      <ComboboxOptions
        v-if="filteredOptions.length > 0"
        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
      >
        <ComboboxOption
          v-for="option in filteredOptions"
          :key="option"
          :value="option"
          as="template"
          v-slot="{ active, selected }"
        >
          <li
            :class="[
              'relative cursor-default select-none py-2 pl-8 pr-4',
              active ? 'bg-indigo-600 text-white' : 'text-gray-900',
            ]"
          >
            <span :class="['block truncate', selected && 'font-semibold']">
              {{ option }}
            </span>

            <span
              v-if="selected"
              :class="[
                'absolute inset-y-0 left-0 flex items-center pl-1.5',
                active ? 'text-white' : 'text-indigo-600',
              ]"
            >
              <CheckIcon class="h-5 w-5" aria-hidden="true" />
            </span>
          </li>
        </ComboboxOption>
      </ComboboxOptions>
    </div>
  </Combobox>
</template>

<script setup lang="ts">
import { computed, PropType, ref } from 'vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import {
  Combobox,
  ComboboxButton,
  ComboboxInput,
  ComboboxLabel,
  ComboboxOption,
  ComboboxOptions,
} from '@headlessui/vue';

const emit = defineEmits(['update:model-value']);

const props = defineProps({
  options: {
    type: Array as PropType<string[]>,
    required: true,
  },

  label: {
    type: String,
    required: false,
    default: undefined,
  },

  required: {
    type: Boolean,
    required: false,
    default: false,
  },
});

const modelValue = defineModel({ type: String, required: true });
const selected = computed({
  get() {
    return modelValue.value;
  },
  set(value) {
    emit('update:model-value', value);
  },
});

const selectedPerson = ref(null);
const query = ref('');
const filteredOptions = computed(() =>
  query.value === ''
    ? props.options
    : props.options.filter((option: string) => {
        return option.includes(query.value.toLowerCase());
      })
);
</script>
