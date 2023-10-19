<template>
  <PageTitle :title="$t('pages.servers.show.title', { name: server?.name ?? '' })"/>

  <Suspense>
    <template #fallback>

    </template>
  </Suspense>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { ServerShowRequest } from '../../Communications/McManager/Servers/ServerShowRequest';
import PageTitle from '../../Components/Layout/PageTitle.vue';
import Server = App.Models.Server;

export default defineComponent({
  components: {
    PageTitle,
  },

  data () {
    return {
      id: this.$attrs.route_parameters?.id as number,
      server: {} as Server,
      showRequest: new ServerShowRequest(),
    };
  },

  methods: {},

  computed: {},

  async mounted () {
    this.server = (await this.showRequest
      .setId(this.id)
      .getResponse())
      .data;
  },
});
</script>
