<template>
  <div>
    <span class="block text-sm font-medium leading-6 text-gray-900">
      {{ label }}
    </span>

    <div class="mt-2 flex items-center gap-x-3">
      <img
        class="h-24 w-24 rounded text-gray-300"
        aria-hidden="true"
        :src="imageUrl"
        :alt="$t('components.file_editor.uploaded_alt')"
      />

      <button
        ref="dropzoneUploadButton"
        class="cursor-pointer rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
      >
        {{ buttonText ?? $t('components.profile_upload.default_button_text') }}
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const emit = defineEmits(['change']);

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

  buttonText: {
    type: String,
    required: false,
    default: undefined,
  },

  defaultImageUrl: {
    type: String,
    required: false,
    default: '',
  },
});

const imageUrl = ref(props.defaultImageUrl);

const dropzoneUploadButton = ref<HTMLButtonElement>();

function onFileUpload(event) {
  const file = event.target.files[0];
  imageUrl.value = URL.createObjectURL(file);
  emit('change', file);
}
</script>
