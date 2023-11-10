<template>
  <button @click="requestLogs">Request</button>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import ServerShowTemplate from '../ServerShowTemplate.vue';
import { Channel } from 'laravel-echo';
import { useServerShowStore } from '../../../Stores/Servers/ServerShowStore';
import { generateStream } from '../../../Communications/McManager/Servers/ServerLogStreamRequest';

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
    async requestLogs () {
      const stream = await generateStream(this.store.model.id);

      for await (const chunk of stream) {
        console.log(chunk);
      }
    },
  },

  mounted () {
    this.channel.listen('.log-update', (a, b, c) => {
      console.log(a, b, c);
    });
  },
});
</script>
