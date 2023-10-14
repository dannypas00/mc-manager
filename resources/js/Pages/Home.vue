<template>
  <PageTitle :title="$t('pages.dashboard.title')"/>

  <div class="grid gap-4 grid-cols-1 sm:grid-cols-2">
    <ServerListing v-for="server in servers" :server="server"/>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import PageTitle from '../Components/Layout/PageTitle.vue';
import ServerListing from '../Components/Server/ServerListing.vue';

export default defineComponent({
  components: {
    ServerListing,
    PageTitle,
  },

  data () {
    return {
      servers: [
        {
          id: 1,
          icon: 'dev-images/plains-icon.png',
          maxPlayers: 10,
          currentPlayers: 5,
          name: 'Plains server',
          description: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam, quia.',
        },
        {
          id: 2,
          icon: 'dev-images/cavern-icon.png',
          maxPlayers: 30,
          currentPlayers: 7,
          name: 'Cavern server',
          description: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam, quia.',
        },
        {
          id: 3,
          icon: 'dev-images/desert-icon.png',
          maxPlayers: 20,
          currentPlayers: 11,
          name: 'Desert server',
          description: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam, quia.',
        },
      ],
    };
  },
});
</script>
