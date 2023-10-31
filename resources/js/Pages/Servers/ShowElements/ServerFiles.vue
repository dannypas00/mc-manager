<template>
  <template v-if="openedFile !== null">
    <Suspense>
      <ServerFileEditor :file="openedFile"/>

      <template #fallback>
        Loading editor...
      </template>
    </Suspense>
  </template>

  <ServerFileList
    v-else
    v-model:selected-files="selectedFiles"
    :entries="data"
    :is-root="['', '/'].includes(path)"
    @go-to-dir="goToDir"
    @go-up="goUp"
    @open-file="openFile"
  />
</template>

<script lang="ts">
import { defineAsyncComponent, defineComponent } from 'vue';
import { StorageListingRequest } from '../../../Communications/McManager/Storage/StorageListingRequest';
import { useServerShowStore } from '../../../Stores/Servers/ServerShowStore';
import ServerFileList from '../Files/ServerFileList.vue';
import { FileEntry } from '../../../Types/FileEntry';

export default defineComponent({
  components: {
    ServerFileEditor: defineAsyncComponent(() => import('../Files/ServerFileEditor.vue')),
    ServerFileList,
  },

  data () {
    return {
      serverStore: useServerShowStore(),
      storageListingRequest: new StorageListingRequest(),
      data: [],
      selectedFiles: [],
      path: '',
      openedFile: null as null | FileEntry,
    };
  },

  methods: {
    goUp () {
      this.goToDir(this.path.replace(/\/?[^\/]*$/, ''));
    },

    goToDir (path: string) {
      this.path = path;
      this.storageListingRequest
        .setPath(path)
        .setServerId(this.serverStore.model.id)
        .getResponse()
        .then(response => {
          this.data = [];
          this.data = response.data;
        });
    },

    openFile (file: FileEntry) {
      this.openedFile = file;
    },
  },

  mounted () {
    this.goToDir('');
  },
});
</script>
