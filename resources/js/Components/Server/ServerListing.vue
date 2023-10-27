<template>
  <a
    class="flex gap-2 rounded-lg p-3 w-full cursor-pointer"
    href=""
    :class="serverClass"
  >
    <div class="shrink-0">
      <img
        class="w-full border border-gray-300 bg-white text-gray-300 w-20 h-20 sm:w-24 sm:h-24 rounded-sm"
        aria-hidden="true"
        :src="server.icon"
        :alt="server.name + ' server icon'"
      >
    </div>

    <div class="grow h-fit">
      <h4 class="text-lg font-bold">{{ server.name }}</h4>
      <p class="mt-1 line-clamp-2 sm:line-clamp-3 h-fit">
        {{ server.description }}
      </p>
    </div>

    <div class="text-end justify-self-end shrink-0 pe-2">
      <p>{{ server.current_players }} <span class="font-semibold">/ {{ server.maximum_players }}</span></p>
    </div>
  </a>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import Server = App.Models.Server;

export default defineComponent({
  props: {
    server: {
      type: Object as PropType<Server>,
      required: true,
    },
  },

  computed: {
    serverClass () {
      switch (this.server.status) {
        case 'up':
          return 'bg-emerald-100 text-emerald-950';
        case 'down':
          return 'bg-gray-200 text-slate-950';
        default:
          return 'bg-red-100 text-red-950';
      }
    },
  },
});
</script>
