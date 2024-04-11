<template>
  <DataTable
    v-bind="{
      headers,
      identifier,
      selectable,
      rowClickable,
      bulkActions,
    }"
    v-model:selected="selected"
    :data="data.data"
  />

  <WidePagination v-model="currentPage" :last-page="data.meta.last_page" />
</template>

<script setup lang="ts" generic="T extends Record<string, any>">
import DataTable from "./DataTable.vue";
import { QueryBuilderIndexRequest } from "../../Communication/Base/QueryBuilderIndexRequest";
import { BulkOption, TableHeader } from "./DataTableTypes";
import { computed, ModelRef, onMounted, PropType, Ref, ref, watch } from "vue";
import { AxiosResponse } from "axios";
import { QueryBuilderIndexData } from "../../Communication/Base/QueryBuilderRequest";
import { useDebounceFn } from "@vueuse/core";
import WidePagination from "../Pagination/WidePagination.vue";

const emit = defineEmits(["update:selected"]);

const selectedModel: ModelRef<Array<T>> = defineModel("selected", {
  type: Array<T>,
  required: false,
  default: undefined,
});

const selected = computed({
  get: () => selectedModel.value,
  set: (value) => emit("update:selected", value),
});

const props = defineProps({
  headers: {
    type: Array as PropType<TableHeader[]>,
    required: true,
  },

  identifier: {
    type: String,
    required: false,
    default: "id",
  },

  selectable: {
    type: Boolean,
    required: false,
    default: false,
  },

  rowClickable: {
    type: Boolean,
    required: false,
    default: false,
  },

  bulkActions: {
    type: Array as PropType<BulkOption<T>[]>,
    required: false,
    default: () => [],
  },

  request: {
    type: Object as PropType<QueryBuilderIndexRequest<T>>,
    required: true,
  },
});

const data: Ref<QueryBuilderIndexData<T[]>> = ref({
  data: [],
  links: [],
  meta: {
    current_page: -1,
    last_page: -1,
    from: -1,
    per_page: -1,
    to: -1,
    total: -1,
  },
});

const currentPage: Ref<number> = ref(1);

const requestData = useDebounceFn(getData);

function getData() {
  console.log("Getting data");
  // TODO: Request cancelling
  // props.request.cancel('Changing filters during request');
  props.request
    .setPage(currentPage.value)
    .getResponse()
    .then((response: AxiosResponse) => {
      data.value = response.data;
    });
}

watch(currentPage, requestData);

onMounted(getData);
</script>
