<template>
  <CodeEditor v-if="fileData !== null" v-model="fileData" class="w-full h-full"/>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import { FileEntry } from '../../../../Types/FileEntry';
import { StorageContentRequest } from '../../../../Communications/McManager/Storage/StorageContentRequest';
import { useServerShowStore } from '../../../../Stores/Servers/ServerShowStore';
import CodeEditor from '../../../../Components/Editor/CodeEditor.vue';

// TODO: There is probably a v-model loop or something here because editing anything makes the page crash
export default defineComponent({
  components: { CodeEditor },

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
      fileData: null as null | string,
    };
  },

  async mounted () {
    await this.request.setServerId(this.store.model.id)
      .setPath(this.file.path)
      .getResponse()
      .then(response => {
        this.fileData = response.data.content
      });
  },
});
</script>
