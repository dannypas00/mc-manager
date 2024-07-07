<template>
  <PageHeader
    :title="$t('pages.settings.title')"
    :header="$t('pages.settings.header')"
  />
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import PageHeader from '../../Components/Layout/PageHeader.vue';

export default defineComponent({
  components: {
    PageHeader,
  },

  data() {
    return {};
  },

  methods: {},

  computed: {},
});
</script>
