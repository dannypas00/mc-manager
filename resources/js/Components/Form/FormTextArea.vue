<template>
  <div>
    <label
      v-if="label"
      for="about"
      class="block text-sm font-medium leading-6 mb-2 text-gray-900"
    >
      {{ label }}
    </label>

    <textarea
      :id="id"
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

  description: {
    type: String,
    required: false,
    default: undefined,
  },
});
</script>
