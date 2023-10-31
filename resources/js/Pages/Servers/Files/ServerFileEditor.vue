<template>
  <CodeEditor v-if="fileData !== null" v-model="fileData"/>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import CodeEditor from '../../../Components/Editor/CodeEditor.vue';
import { StorageContentRequest } from '../../../Communications/McManager/Storage/StorageContentRequest';
import { useServerShowStore } from '../../../Stores/Servers/ServerShowStore';
import { FileEntry } from '../../../Types/FileEntry';

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

  methods: {},

  computed: {},

  async mounted () {
    console.log(this.file);
    // Do file request
    await this.request.setServerId(this.store.model.id)
      .setPath(this.file.path)
      .getResponse()
      .then(response => {
        console.log(response);
        this.fileData = response.data.content
      });
  },
});
</script>
