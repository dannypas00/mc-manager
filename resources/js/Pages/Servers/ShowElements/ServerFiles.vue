<template>
  {{ data }}
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { StorageListingRequest } from '../../../Communications/McManager/Storage/StorageListingRequest';
import { useServerShowStore } from '../../../Stores/Servers/ServerShowStore';

export default defineComponent({
  components: {},

  data () {
    return {
      serverStore: useServerShowStore(),
      storageListingRequest: new StorageListingRequest(),
      data: {},
    };
  },

  mounted () {
    this.storageListingRequest
      .setPath('/')
      .setServerId(this.serverStore.model.id)
      .getResponse()
      .then(response => {
        this.data = response.data;
      });
  },
});
</script>
