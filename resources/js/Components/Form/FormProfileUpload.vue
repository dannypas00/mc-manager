<template>
  <div class="">
    <span class="block text-sm font-medium leading-6 text-gray-900">
      {{ label }}
    </span>

    <div
      ref="imageDropzone"
      class="mt-2 flex items-center gap-x-3 w-fit rounded-xl bg-blend-overlay box-border p-2"
      :class="{ 'outline-2 outline-dashed outline-indigo-500 bg-indigo-50': isOverDropZone }"
    >
      <img
        class="h-24 w-24 rounded text-gray-300"
        aria-hidden="true"
        :src="imageUrl"
        :alt="$t('components.file_editor.uploaded_alt')"
      />

      <button
        ref="imageUploadButton"
        class="cursor-pointer rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
        @click="() => open()"
      >
        {{ buttonText ?? $t('components.profile_upload.default_button_text') }}
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useDropZone, useFileDialog } from '@vueuse/core';

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

const imageUploadButton = ref<HTMLButtonElement>();
const imageDropzone = ref<HTMLDivElement>();

const { isOverDropZone } = useDropZone(imageDropzone, {
  onDrop: (files: File[] | null) => {
    if (files) {
      onFileUpload(files[0]);
    }
  },
});

const { open, onChange } = useFileDialog({
  accept: 'image/*',
  multiple: false,
});

onChange((files: FileList | null) => {
  if (files) {
    onFileUpload(files[0]);
  }
});

function onFileUpload (file: File) {
  imageUrl.value = URL.createObjectURL(file);
  emit('change', file);
}
</script>
