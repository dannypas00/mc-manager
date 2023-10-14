<template>
  <PageTitle :title="$t('pages.dashboard.title')"/>

  {{ $t("pages.servers.test.test2") }}
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import PageTitle from '../Components/Layout/PageTitle.vue';

export default defineComponent({
  components: {
    PageTitle,
  },
});
</script>
