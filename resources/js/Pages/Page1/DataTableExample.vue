<template>
  <PageHeader
    :title="$t('pages.page1.title')"
    :header="$t('pages.page1.title')"
  >
    <IconButton
      :text="$t('pages.page1.header.new_button')"
      :icon="{ icon: 'fas fa-plus' }"
      class="bg-green-600 text-white hover:bg-green-500 focus-visible:outline-green-600"
      @click="createModalOpen = true"
    />
  </PageHeader>

  <QueryBuilderTable
    v-model:selected="selected"
    :request="request"
    :headers="getTableHeaders()"
    :bulk-actions="getBulkOptions()"
    selectable
  />

  <ModalDialog v-model:open="createModalOpen" title="Create new user">
    <template #content>
      <UserCreateForm @submit="onUserCreate" />
    </template>
  </ModalDialog>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import QueryBuilderTable from "../../Components/DataTable/QueryBuilderTable.vue";
import { UserIndexRequest } from "../../Communication/Users/UserIndexRequest";
import {
  BulkOption,
  CreatedAtHeader,
  IdHeader,
  TableHeader,
  UpdatedAtHeader,
} from "../../Components/DataTable/DataTableTypes";
import PageHeader from "../../Components/Layout/PageHeader.vue";
import { UserData } from "../../Types/generated";
import { faPlus, faTrash } from "@fortawesome/free-solid-svg-icons";
import ModalDialog from "../../Components/Dialogs/ModalDialog.vue";
import IconButton from "../../Components/Buttons/IconButton.vue";
import UserCreateForm from "./Partials/UserCreateForm.vue";

export default defineComponent({
  components: {
    UserCreateForm,
    IconButton,
    ModalDialog,
    QueryBuilderTable,
    PageHeader,
  },

  data() {
    return {
      request: new UserIndexRequest(),
      selected: [],
      createModalOpen: true,
    };
  },

  methods: {
    getTableHeaders(): TableHeader[] {
      return [
        IdHeader,
        {
          key: "name",
          title: this.$t("pages.page1.table.name_title"),
        },
        {
          key: "email",
          title: this.$t("pages.page1.table.email_title"),
        },
        CreatedAtHeader,
        UpdatedAtHeader,
      ];
    },

    getBulkOptions(): BulkOption<UserData>[] {
      return [
        {
          title: "Delete",
          onClick: (selected: UserData[]) => {
            console.log("Clicked bulk delete!", selected);
          },
          unselectAfter: true,
          icon: {
            icon: faTrash,
          },
          classes: "bg-red-500 hover:bg-red-700 border-red-400 text-white",
          confirmation: true,
          confirmationText: (selected: UserData[]) =>
            this.$t("pages.page1.table.delete_text", selected),
        },
        {
          title: "Test",
          unselectAfter: false,
        },
      ] as BulkOption<UserData>[];
    },

    getHeaderButtons(): PageHeaderButton[] {
      return [
        {
          text: this.$t("pages.page1.header.new_button"),
          icon: {
            icon: faPlus,
          },
          additionalClasses:
            "text-white bg-green-400 hover:bg-green-600 active:ring-green-600",
          onClick: () => {
            this.createModalOpen = true;
          },
        },
        {
          text: "Test",
          href: route("page2") as string,
        },
      ];
    },

    onUserCreate() {
      this.createModalOpen = false;
    },
  },

  computed: {},
});
</script>
