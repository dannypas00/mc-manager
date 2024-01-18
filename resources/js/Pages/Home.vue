<template>
  <PageTitle :title="$t('pages.dashboard.title')"/>

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
