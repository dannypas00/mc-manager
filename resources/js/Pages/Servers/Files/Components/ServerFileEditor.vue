<template>
  <div class="h-[60vh] w-full">
    <VueMonacoEditor
      v-model:value="fileData"
      theme="vs-dark"
      :options="monacoOptions"
    />
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import { FileEntry } from '../../../../Types/FileEntry';
import { StorageContentRequest } from '../../../../Communications/McManager/Storage/StorageContentRequest';
import { useServerShowStore } from '../../../../Stores/Servers/ServerShowStore';
import { VueMonacoEditor } from '@guolao/vue-monaco-editor';
import { StorageWriteRequest } from '../../../../Communications/McManager/Storage/StorageWriteRequest';
import { useToast } from 'vue-toastification';

export default defineComponent({
  components: {
    VueMonacoEditor,
  },

  props: {
    file: {
      type: Object as PropType<FileEntry>,
      required: true,
    },
  },

  data () {
    return {
      store: useServerShowStore(),
      request: new StorageContentRequest(),
      writeRequest: new StorageWriteRequest(),
      fileData: '',
      originalFileData: '',
      monacoOptions: {
        automaticLayout: true,
        formatOnType: true,
        formatOnPaste: true,
      },
    };
  },

  methods: {
    save () {
      if (!this.unsavedChanges) {
        useToast().warning(this.$t('components.file_editor.nothing_to_save'));
        return;
      }
      this.writeRequest
        .setId(this.store.model.id)
        .setPath(this.file.path)
        .setContent(this.fileData)
        .getResponse()
        .then(() => {
          useToast().success(this.$t('components.file_editor.save_sucessful', { name: this.file.path }));
          this.originalFileData = this.fileData;
        });
    },

    onBeforeUnload (e: BeforeUnloadEvent) {
      e.preventDefault();

      e.returnValue = $t('components.file_editor.confirm_unsaved_changes');
    },
  },

  computed: {
    unsavedChanges () {
      return this.fileData !== this.originalFileData;
    },
  },

  watch: {
    unsavedChanges (value: boolean) {
      if (value) {
        window.addEventListener('beforeunload', this.onBeforeUnload);
        return;
      }

      window.removeEventListener('beforeunload', this.onBeforeUnload);
    },
  },

  async mounted () {
    const response = await this.request
      .setServerId(this.store.model.id)
      .setPath(this.file.path)
      .getResponse();

    this.fileData = response.data.content;
    this.originalFileData = response.data.content;
  },
});
</script>
