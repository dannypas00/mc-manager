<template>
  <div>
    <label
      v-if="label"
      :for="id"
      class="block text-sm font-medium leading-6 mb-2 text-gray-900"
    >
      {{ label }}
    </label>

    <div class="flex rounded-md shadow-sm ring-1 ring-inset pl-2 ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
    <span v-if="$slots.prefix" class="flex select-none items-center pl-1 text-gray-500 sm:text-sm">
      <slot name="prefix"/>
    </span>

      <input
        :id="id"
        :placeholder="placeholder"
        :name="label"
        type="text"
        class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
      >
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps({
  modelValue: {
    type: String,
    required: true,
  },

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
});
</script>
