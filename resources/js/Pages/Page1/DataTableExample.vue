<template>
  <PageHeader :title="$t('pages.page1.title')" :header="$t('pages.page1.title')"/>

  <QueryBuilderTable :request="request" :headers="getTableHeaders()"/>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import DataTable from '../../Components/DataTable/DataTable.vue';
import QueryBuilderTable from '../../Components/DataTable/QueryBuilderTable.vue';
import { UserIndexRequest } from '../../Communication/Users/UserIndexRequest';
import { CreatedAtHeader, IdHeader, TableHeader, UpdatedAtHeader } from '../../Components/DataTable/DataTableTypes';
import PageHeader from '../../Components/Layout/PageHeader.vue';

export default defineComponent({
  components: {
    QueryBuilderTable,
    DataTable,
    PageHeader,
  },

  data () {
    return {
      request: new UserIndexRequest(),
    };
  },

  methods: {
    getTableHeaders (): TableHeader[] {
      return [
        IdHeader,
        {
          key: 'name',
          title: this.$t('pages.page1.table.name_title'),
        },
        {
          key: 'email',
          title: this.$t('pages.page1.table.email_title'),
        },
        CreatedAtHeader,
        UpdatedAtHeader,
      ];
    },
  },

  computed: {},
});
</script>
