<template>
  <!-- Terminal -->
  <div class="w-[90%] h-[50em] bg-slate-900 text-white mx-auto fs-12 overflow-auto" ref="log">
    <!-- Text render -->
    <code v-if="!useFancyLog" class="whitespace-pre-line">{{ log }}</code>
    <FancyLog v-else :log="log"/>

    <div style="overflow-anchor: auto; height: 1px"/>

    <!-- Inputbox -->
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import ServerShowTemplate from '../ServerShowTemplate.vue';
import { useServerShowStore } from '../../../Stores/Servers/ServerShowStore';
import FancyLog from './FancyLog.vue';

export default defineComponent({
  components: { FancyLog },
  layout: ServerShowTemplate,

  data () {
    return {
      store: useServerShowStore(),
      log: '',
      useFancyLog: true,
    };
  },

  watch: {
    log (newLog, oldLog) {
      if (oldLog === '') {
        this.$nextTick(() => {
          console.log('scroll');
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
