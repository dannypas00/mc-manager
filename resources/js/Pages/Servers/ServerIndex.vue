<template>
  <PageTitle :title="$t('pages.servers.index.title')">
    <PositiveButton
      :text="$t('pages.servers.index.add_button_text`')"
      :href="$route('servers.create')"
      is-link
      class="h-full"
    >
      <PlusIcon class="h-full"/>
    </PositiveButton>
  </PageTitle>

  <div class="grid gap-4 grid-cols-1 sm:grid-cols-2">
    <Link
      v-for="server in userStore.user.servers"
      :key="server.id"
      :href="$route('servers.show', { id: server.id })"
      as="a"
    >
      <ServerListing :initial-server="server"/>
    </Link>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { Link } from '@inertiajs/vue3';
import ServerListing from '../../Components/Server/ServerListing.vue';
import PageTitle from '../../Components/Layout/PageTitle.vue';
import { useUserStore } from '../../Stores/UserStore';
import PositiveButton from '../../Components/Buttons/PositiveButton.vue';
import { PlusIcon } from '@heroicons/vue/24/solid';

export default defineComponent({
  components: {
    PositiveButton,
    PageTitle,
    ServerListing,
    Link,
    PlusIcon,
  },

  data () {
    return {
      userStore: useUserStore(),
    };
  },

  methods: {},

  computed: {},
});
</script>
