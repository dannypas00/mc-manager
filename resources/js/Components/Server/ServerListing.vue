<template>
  <a
    class="flex w-full cursor-pointer gap-2 rounded-lg p-3"
    href=""
    :class="serverClass"
  >
    <div class="shrink-0">
      <img
        class="h-20 w-20 w-full rounded-sm border border-gray-300 bg-white text-gray-300 sm:h-24 sm:w-24"
        aria-hidden="true"
        :src="server.icon"
        :alt="server.name + ' server icon'"
      />
    </div>

    <div class="h-fit grow">
      <h4 class="text-lg font-bold">{{ server.name }}</h4>
      <p class="mt-1 line-clamp-2 h-fit sm:line-clamp-3">
        {{ server.description }}
      </p>
    </div>

    <div class="shrink-0 justify-self-end pe-2 text-end">
      <p :title="$t('pages.servers.listing.player_list') + server.player_list">
        {{ server.current_players }}
        <span class="font-semibold">/ {{ server.maximum_players }}</span>
      </p>
    </div>
  </a>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import Server = App.Models.Server;

export default defineComponent({
  props: {
    initialServer: {
      type: Object as PropType<Server>,
      required: true,
    },
  },

  data() {
    return {
      server: {} as Server,
    };
  },

  computed: {
    serverClass() {
      switch (this.server.status) {
        case 'up':
          return 'bg-emerald-100 text-emerald-950';
        case 'down':
          return 'bg-gray-200 text-slate-900';
        default:
          return 'bg-red-100 text-red-950';
      }
    },
  },

  mounted() {
    this.server = this.initialServer;

    window.Echo.channel(`servers.${this.initialServer.id}`).listen(
      '.update',
      (event: { server: Server }) => {
        this.server = Object.assign(this.server, event.server);
      }
    );
  },
});
</script>
