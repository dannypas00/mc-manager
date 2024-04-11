<template>
  <PageHeader :title="$t('pages.page1.title')" :header="$t('pages.page1.title')" :buttons="getHeaderButtons()"/>

  <QueryBuilderTable
    :request="request"
    :headers="getTableHeaders()"
    :bulk-actions="getBulkOptions()"
    v-model:selected="selected"
    selectable
  />

  <Modal
    v-model:open="createModalOpen"
    positive="Create"
    negative="Cancel"
    title="Create new user"
  >
    <template #content>
      Hi!
    </template>
  </Modal>
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
import { faPlus, faTrash } from '@fortawesome/free-solid-svg-icons';
import Modal from '../../Components/Dialogs/Modal.vue';
import { PageHeaderButton } from '../../Components/Layout/PageHeaderButton';

export default defineComponent({
  components: {
    Modal,
    QueryBuilderTable,
    DataTable,
    PageHeader,
  },

  data () {
    return {
      request: new UserIndexRequest(),
      selected: [],
      createModalOpen: true,
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

  getHeaderButtons (): PageHeaderButton[] {
      return [
        {
          text: this.$t('pages.page1.header.new_button'),
          icon: {
            icon: faPlus,
          },
          additionalClasses: 'text-white bg-green-400 hover:bg-green-600 active:ring-green-600',
          onClick: () => {
            this.createModalOpen = true;
          },
        },
        {
          text: 'Test',
          href: route('page2')
        },
      ];
    },
  },

  computed: {},
});
</script>
