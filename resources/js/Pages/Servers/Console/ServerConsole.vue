<template>
  <!-- Terminal -->
  <div class="w-full h-[50em] bg-slate-900 text-white mx-auto fs-12">
    <!-- Text render -->
    <code class="m-1">
      {{ log }}
    </code>

    <!-- Inputbox -->
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import ServerShowTemplate from '../ServerShowTemplate.vue';
import { useServerShowStore } from '../../../Stores/Servers/ServerShowStore';

export default defineComponent({
  layout: ServerShowTemplate,

  data () {
    return {
      store: useServerShowStore(),
      log: '',
    };
  },

  methods: {
  },

  mounted () {
    const stream = new EventSource(route('api.servers.logs', { id: this.store.model.id }));
    stream.addEventListener('ping', event => {
      const newData = atob(event.data);
      this.log += newData;
      console.log(newData);
    });
  },
});
</script>
