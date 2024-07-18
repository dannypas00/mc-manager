<template>
  <input
    v-model="value"
    type="date"
    class="rint-inset w-full rounded-md border-0 bg-none p-1.5 text-gray-400 ring-1 ring-gray-300 focus:ring-2 focus:ring-brand-light"
    :class="{ 'has-input': !!value }"
    :min="min"
  />
</template>

<script setup lang="ts">
import { computed, ModelRef, WritableComputedRef } from 'vue';

const emit = defineEmits(['update:model-value']);

const props = defineProps({
  modelValue: {
    type: Date,
    required: true,
  },
  min: {
    type: String,
    required: false,
  },
});

const model: ModelRef<Date> = defineModel({ required: true });

const value: WritableComputedRef<Date> = computed({
  get() {
    return model.value;
  },
  set(value) {
    emit('update:model-value', value);
  },
});
</script>
