<template>
  <div class="w-max-[90%] fs-12 mx-auto w-[90%] bg-slate-900 text-white">
    <!-- Terminal -->
    <div class="h-[50em] overflow-y-auto" ref="log">
      <!-- Text render -->
      <code v-if="!useFancyLog" class="whitespace-pre-line">{{ log }}</code>
      <FancyLog v-else :log="log" />

      <div style="overflow-anchor: auto; height: 1px" />
    </div>
    <!-- Inputbox -->
    <form
      class="flex h-7 border-t-2 border-slate-300 px-1"
      @submit.prevent="onCommandSubmit"
    >
      <CodeBracketIcon class="inline-block h-6 w-6" />
      <input
        v-model="command"
        class="inline-block h-6 grow border-0 bg-transparent p-0 px-2 outline-0 focus:border-0 focus:ring-0"
      />
    </form>
  </div>
</template>

<script lang="ts">
import { defineComponent, unref } from 'vue';
import ServerShowTemplate from '../ServerShowTemplate.vue';
import { useServerShowStore } from '../../../Stores/Servers/ServerShowStore';
import FancyLog from './FancyLog.vue';
import CodeBracketIcon from '@heroicons/vue/20/solid/CodeBracketIcon';
import moment from 'moment';
import { ServerRconCommandRequest } from '../../../Communications/McManager/Servers/ServerRconCommandRequest';

export default defineComponent({
  components: {
    FancyLog,
    CodeBracketIcon,
  },

  layout: ServerShowTemplate,

  data() {
    return {
      store: useServerShowStore(),
      log: '',
      useFancyLog: true,
      command: '',
      rconRequest: new ServerRconCommandRequest(),
    };
  },

  methods: {
    onCommandSubmit() {
      this.rconRequest
        .setId(this.store.model.id)
        .setCommand(this.command)
        .getResponse();

      this.command = '';
    },

    stream(start?: number) {
      const stream = new EventSource(
        route('api.servers.logs', { id: this.store.model.id, start })
      );

      stream.addEventListener('ping', event => {
        // Decode data from base64 to plain
        const newData: string = atob(event.data);
        // Remove any RCON connection logs because every command logs an RCON connection
        const filteredData = newData.replace(/.*Thread RCON Client.*/g, '');
        this.log += filteredData.trim() + '\n';
      });

      stream.addEventListener('end', event => {
        stream.close();
        this.stream(event.data);
      });
    },
  },

  watch: {
    log(newLog, oldLog) {
      if (oldLog === '') {
        this.$nextTick(() => {
          this.$refs.log.scrollTop = this.$refs.log.scrollHeight;
        });
      }
    },
  },

  mounted() {
    this.stream();
  },
});
</script>
