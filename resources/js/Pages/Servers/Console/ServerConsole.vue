<template>
  <button @click="requestLogs">Request</button>

  <!-- Terminal -->
  <div class="w-full h-[50em] bg-slate-900 text-white mx-auto">
    <!-- Text render -->
    <code class="m-1">
      test
    </code>

    <!-- Inputbox -->
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import ServerShowTemplate from '../ServerShowTemplate.vue';
import { Channel } from 'laravel-echo';
import { useServerShowStore } from '../../../Stores/Servers/ServerShowStore';

export default defineComponent({
  layout: ServerShowTemplate,

  data () {
    return {
      channel: window.Echo.channel('log-message') as Channel,
      lastLine: 0,
      store: useServerShowStore(),
    };
  },

  methods: {
    requestLogs () {
    },
  },

  mounted () {
    const stream = new EventSource(route('api.servers.logs', { id : this.store.model.id }));
    stream.addEventListener('ping', event => {
      console.log(event);
    })
  },
});
</script>
