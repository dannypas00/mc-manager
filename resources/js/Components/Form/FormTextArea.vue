<template>
  <div>
    <label
      v-if="label"
      for="about"
      class="mb-2 block text-sm font-medium leading-6 text-gray-900"
    >
      {{ label }}
    </label>

    <textarea
      :id="id"
      v-model="value"
      :name="label"
      rows="3"
      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
    />

    <p v-if="description" class="mt-3 text-sm leading-6 text-gray-600">
      {{ description }}
    </p>
  </div>
</template>

<script setup lang="ts">
import { computed, ModelRef, PropType, WritableComputedRef } from 'vue';

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

  description: {
    type: String,
    required: false,
    default: undefined,
  },
});

const modelValue: ModelRef<string | null> = defineModel({
  type: [String, null] as PropType<string | null>,
  required: true,
});

const value: WritableComputedRef<string | null> = computed({
  get (): string | null {
    return modelValue.value;
  },
  set (value) {
    emit('update:model-value', value);
  },
});
</script>
