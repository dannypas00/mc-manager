<template>
  <ServerForm v-if="serverStore.model" :type="serverStore.model.type" />
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import ServerForm from './Form/ServerForm.vue';
import { useServerEditStore } from '../../Stores/Servers/ServerEditStore';
import Server = App.Models.Server;

export default defineComponent({
  components: {
    ServerForm,
  },

  props: {
    server: {
      type: Object as PropType<Server>,
      required: true,
    },
  },

  data() {
    return {
      serverStore: useServerEditStore(),
    };
  },

  mounted() {
    this.serverStore.model = this.server;
  },
});
</script>
