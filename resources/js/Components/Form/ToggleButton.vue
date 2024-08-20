<template>
  <SwitchGroup as="div" class="flex cursor-pointer select-none items-center">
    <Switch
      v-model="value"
      :class="[
        value ? 'bg-brand' : 'bg-gray-200',
        'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2',
      ]"
    >
      <span
        aria-hidden="true"
        :class="[
          value ? 'translate-x-5' : 'translate-x-0',
          'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
        ]"
      />
    </Switch>
    <SwitchLabel as="span" class="ml-3 text-sm" v-if="label">
      <span class="font-medium text-gray-900">{{ label }}</span>
    </SwitchLabel>
  </SwitchGroup>
</template>

<script setup lang="ts">
import { Switch, SwitchGroup, SwitchLabel } from '@headlessui/vue';
import { computed } from 'vue';

const emit = defineEmits(['update:model-value']);

defineProps({
  label: {
    type: String,
    required: false,
    default: undefined,
  },
});

const enabled = defineModel({ required: true });

const value = computed({
  get() {
    return Boolean(enabled.value);
  },
  set(value) {
    emit('update:model-value', Boolean(value));
  },
});
</script>
