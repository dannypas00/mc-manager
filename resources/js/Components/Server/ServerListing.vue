<template>
  <div class="flex gap-2 bg-emerald-100 rounded-lg p-3 w-full text-emerald-950">
    <div class="shrink-0">
      <img
        class="w-full border border-gray-300 bg-white text-gray-300 h-20 sm:w-32 rounded-sm"
        aria-hidden="true"
        :src="server.icon"
        :alt="server.name + ' server icon'"
      >
    </div>

    <div class="grow h-fit">
      <h4 class="text-lg font-bold">Lorem ipsum</h4>
      <p class="mt-1 line-clamp-2 sm:line-clamp-3 h-fit">
        {{ server.description }}
      </p>
    </div>

    <div class="text-end justify-self-end shrink-0 pe-2">
      <p>{{ server.currentPlayers }} <span class="font-semibold">/ {{ server.maxPlayers }}</span></p>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';

export default defineComponent({
  props: {
    server: {
      type: Object as PropType<{
        icon: string,
        maxPlayers: number,
        currentPlayers: number,
        name: string,
        description: string,
      }>,
      required: true,
    },
  },
  data () {
    return {};
  },
});
</script>
