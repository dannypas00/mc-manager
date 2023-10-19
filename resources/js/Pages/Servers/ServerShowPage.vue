<template>
  <PageTitle :title="$t('pages.servers.show.title', { name: store.model.name })"/>

  <ServerInformation :id="this.id"/>

  <Suspense>
    <template #fallback>
      <LoadingState/>
    </template>
  </Suspense>
</template>

<script lang="ts">
import { defineAsyncComponent, defineComponent } from 'vue';
import PageTitle from '../../Components/Layout/PageTitle.vue';
import LoadingState from '../../Components/Layout/LoadingState.vue';
import { useServerShowStore } from '../../Stores/Servers/ServerShowStore';

export default defineComponent({
  components: {
    LoadingState,
    PageTitle,
    ServerInformation: defineAsyncComponent(() => import('./ServerInformation.vue')),
  },

  data () {
    return {
      id: this.$attrs.route_parameters?.id as number,
      store: useServerShowStore(),
    };
  },
});
</script>
