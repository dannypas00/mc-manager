<template>
  <ServerForm v-if="serverStore.model" :type="serverStore.model.type"/>

  <div class="w-full pt-4 flex justify-end">
    <PositiveButton class="text-center" :text="$t('general.buttons.save')" @click="save"/>
  </div>

  <FullpageSpinner
    v-if="loading"
    :reason="$t('pages.servers.create.save_loader', { server: serverStore.model.name })"
  />
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import ServerForm from './Form/ServerForm.vue';
import { useServerEditStore } from '../../Stores/Servers/ServerEditStore';
import PositiveButton from '../../Components/Buttons/PositiveButton.vue';
import FullpageSpinner from '../../Components/Layout/FullpageSpinner.vue';
import Server = App.Models.Server;

export default defineComponent({
  components: {
    FullpageSpinner,
    PositiveButton,
    ServerForm,
  },

  props: {
    server: {
      type: Object as PropType<Server>,
      required: true,
    },
  },

  data () {
    return {
      serverStore: useServerEditStore(),
    };
  },

  methods: {
    save () {
      console.log(this.serverStore.requestData);
    },
  },

  mounted () {
    this.serverStore.model = this.server;
  },
});
</script>
