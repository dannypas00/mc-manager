<template>
  <Suspense>
    <DataTable
      v-bind="{
        headers,
        identifier,
        selectable,
        rowClickable,
        bulkOptions,
      }"
      :data="data.data"
    />
    <template #fallback>
      Insert spinner here lol
    </template>
  </Suspense>
</template>

<script setup lang="ts" generic="T extends Record<string, any>">
import { PropType } from 'vue/dist/vue';
import DataTable from './DataTable.vue';
import { QueryBuilderIndexRequest } from '../../Communication/Base/QueryBuilderIndexRequest';
import { BulkOption, TableHeader } from './DataTableTypes';
import { onMounted, Ref, ref } from 'vue';
import { AxiosResponse } from 'axios';
import { QueryBuilderIndexData } from '../../Communication/Base/QueryBuilderRequest';

const props = defineProps({
  headers: {
    type: Array as PropType<TableHeader[]>,
    required: true,
  },

  identifier: {
    type: String,
    required: false,
    default: 'id',
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

  bulkOptions: {
    type: Array as PropType<BulkOption<T>[]>,
    required: false,
    default: [],
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

function requestData () {
  console.log('Requesting data');
  props.request.getResponse()
    .then((response: AxiosResponse) => {
      console.log(response);
      data.value = response.data;
    });
}

onMounted(requestData);
</script>
