<template>
  <div class="w-[90%] bg-slate-900 text-white mx-auto fs-12">
    <!-- Terminal -->
    <div class="h-[50em] overflow-auto" ref="log">
      <!-- Text render -->
      <code v-if="!useFancyLog" class="whitespace-pre-line">{{ log }}</code>
      <FancyLog v-else :log="log"/>

      <div style="overflow-anchor: auto; height: 1px"/>

    </div>
    <!-- Inputbox -->
    <form class="h-7 border-t-2 border-slate-300 px-1 flex" @submit.prevent="onCommandSubmit">
      <CodeBracketIcon class="inline-block h-6 w-6"/>
      <input v-model="command" class="inline-block bg-transparent border-0 focus:border-0 focus:ring-0 p-0 px-2 outline-0 h-6 grow">
    </form>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import ServerShowTemplate from '../ServerShowTemplate.vue';
import { useServerShowStore } from '../../../Stores/Servers/ServerShowStore';
import FancyLog from './FancyLog.vue';
import CodeBracketIcon from '@heroicons/vue/20/solid/CodeBracketIcon';

export default defineComponent({
  components: {
    FancyLog,
    CodeBracketIcon,
  },

  layout: ServerShowTemplate,

  data () {
    return {
      store: useServerShowStore(),
      log: '',
      useFancyLog: true,
      command: '',
    };
  },

  methods: {
    onCommandSubmit () {
      console.log(this.command);
    }
  },

  watch: {
    log (newLog, oldLog) {
      if (oldLog === '') {
        this.$nextTick(() => {
          this.$refs.log.scrollTop = this.$refs.log.scrollHeight;
        });
      }
    },
  },

  mounted () {
    const stream = new EventSource(route('api.servers.logs', { id: this.store.model.id }));

    stream.addEventListener('ping', event => {
      const newData: string = atob(event.data);
      this.log += newData.trim() + '\n';
    });
  },
});
</script>
