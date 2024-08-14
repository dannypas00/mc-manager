<template>
  <div class="w-full">
    <label
      v-if="label"
      :for="id"
      class="mb-2 block text-sm font-medium leading-6 text-gray-900"
    >
      {{ label }}
    </label>

    <div class="flex">
      <div
        class="flex grow rounded-md pl-2 shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md"
      >
        <span
          v-if="$slots.prefix"
          class="flex select-none items-center pl-1 text-gray-500 sm:text-sm"
        >
          <slot name="prefix" />
        </span>

        <input
          :id="id"
          v-model="value"
          :placeholder="placeholder"
          :name="label"
          type="text"
          class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
        />

        <div v-if="explanation" class="inline-block p-2" :title="explanation">
          <FontAwesomeIcon icon="circle-question" />
        </div>
      </div>

      <div
        v-if="warning"
        class="inline-block p-2 text-yellow-500"
        :title="warning"
      >
        <FontAwesomeIcon icon="triangle-exclamation" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { computed } from 'vue';

const emit = defineEmits(['update:model-value']);

const props = defineProps({
  id: {
    type: String,
    required: true,
  },

  label: {
    type: String,
    required: false,
    default: undefined,
  },

  placeholder: {
    type: String,
    required: false,
    default: undefined,
  },

  explanation: {
    type: String,
    required: false,
    default: undefined,
  },

  warning: {
    type: String,
    required: false,
    default: undefined,
  },
});

const modelValue = defineModel();

const value = computed({
  get() {
    return modelValue;
  },
  set() {
    emit('update:model-value');
  },
});
</script>
