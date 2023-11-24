<template>
  <PageTitle :title="$t('pages.dashboard.title')"/>

  <div class="grid gap-4 grid-cols-1 sm:grid-cols-2">
    <Link
      v-for="server in userStore.user.servers"
      :key="server.id"
      :href="$route('servers.show', { id: server.id })"
    >
      <ServerListing :initial-server="server"/>
    </Link>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import PageTitle from '../Components/Layout/PageTitle.vue';
import ServerListing from '../Components/Server/ServerListing.vue';
import { useUserStore } from '../Stores/UserStore';
import { Link } from '@inertiajs/vue3';

export default defineComponent({
  components: {
    ServerListing,
    PageTitle,
    Link,
  },

  data () {
    return {
      userStore: useUserStore(),
    };
  },
});
</script>
