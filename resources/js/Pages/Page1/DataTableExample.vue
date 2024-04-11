<template>
  <PageHeader :title="$t('pages.page1.title')" :header="$t('pages.page1.title')"/>

  <QueryBuilderTable
    :request="request"
    :headers="getTableHeaders()"
    :bulk-actions="getBulkOptions()"
    v-model:selected="selected"
    selectable
  />
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import DataTable from '../../Components/DataTable/DataTable.vue';
import QueryBuilderTable from '../../Components/DataTable/QueryBuilderTable.vue';
import { UserIndexRequest } from '../../Communication/Users/UserIndexRequest';
import {
  BulkOption,
  CreatedAtHeader,
  IdHeader,
  TableHeader,
  UpdatedAtHeader,
} from '../../Components/DataTable/DataTableTypes';
import PageHeader from '../../Components/Layout/PageHeader.vue';
import { UserData } from '../../Types/generated';
import { faTrash } from '@fortawesome/free-solid-svg-icons';

export default defineComponent({
  components: {
    QueryBuilderTable,
    DataTable,
    PageHeader,
  },

  data () {
    return {
      request: new UserIndexRequest(),
      selected: [],
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

    getBulkOptions (): BulkOption<UserData>[] {
      return [
        {
          title: 'Delete',
          onClick: (selected: UserData[]) => {
            console.log('Clicked bulk delete!', selected);
          },
          unselectAfter: true,
          icon: {
            icon: faTrash,
          },
          classes: 'bg-red-500 hover:bg-red-700 border-red-400 text-white',
          confirmation: true,
          confirmationText: (selected: UserData[]) => this.$t('pages.page1.table.delete_text', selected),
        },
        {
          title: 'Test',
          unselectAfter: false,
        },
      ] as BulkOption<UserData>[];
    },
  },

  computed: {},
});
</script>
