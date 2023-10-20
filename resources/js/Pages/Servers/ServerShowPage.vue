<template>
  <PageTitle :title="$t('pages.servers.show.title', { name: store.model.name })" :header="store.model.name"/>

  <ServerInformation/>
</template>

<script lang="ts">
import { defineAsyncComponent, defineComponent, PropType } from 'vue';
import PageTitle from '../../Components/Layout/PageTitle.vue';
import LoadingState from '../../Components/Layout/LoadingState.vue';
import { useServerShowStore } from '../../Stores/Servers/ServerShowStore';
import Server = App.Models.Server;
import ServerInformation from './ServerInformation.vue';

export default defineComponent({
  components: {
    LoadingState,
    ServerInformation,
    PageTitle,
  },

  props: {
    server: {
      type: Object as PropType<Server>,
      required: true,
    },
  },

  data () {
    return {
      store: useServerShowStore(),
    };
  },

  beforeMount () {
    this.store.model = this.server;
  }
});
</script>
