<template>
  <ServerFileList
    v-model:selected-files="selectedFiles"
    :entries="data"
    :is-root="['', '/'].includes(path)"
    @go-to-dir="goToDir"
    @go-up="goUp"
  />
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { StorageListingRequest } from '../../../Communications/McManager/Storage/StorageListingRequest';
import { useServerShowStore } from '../../../Stores/Servers/ServerShowStore';
import ServerFileList from '../Files/ServerFileList.vue';
import _ from 'lodash';

export default defineComponent({
  components: { ServerFileList },

  data () {
    return {
      serverStore: useServerShowStore(),
      storageListingRequest: new StorageListingRequest(),
      data: [],
      selectedFiles: [],
      path: '',
    };
  },

  methods: {
    goUp () {
      console.log(this.path);
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
  },

  mounted () {
    this.goToDir('');
  },
});
</script>
