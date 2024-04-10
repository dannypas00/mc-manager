<template>
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-base font-semibold leading-6 text-gray-900">Users</h1>
        <p class="mt-2 text-sm text-gray-700">A list of all the users in your account including their name, title, email
                                              and role.</p>
      </div>

      <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <button
          type="button"
          class="block rounded-md bg-indigo-600 px-3 py-1.5 text-center text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
        >Add user
        </button>
      </div>
    </div>

    <div class="mt-8 flow-root">
      <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
          <div class="relative">
            <div
              v-if="selected.length > 0"
              class="absolute left-14 top-0 flex h-12 items-center space-x-3 bg-white sm:left-12"
            >
              <button
                type="button"
                class="inline-flex items-center rounded bg-white px-2 py-1 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-30 disabled:hover:bg-white"
              >Bulk edit
              </button>
              <button
                type="button"
                class="inline-flex items-center rounded bg-white px-2 py-1 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-30 disabled:hover:bg-white"
              >Delete all
              </button>
            </div>
            <table class="min-w-full table-fixed divide-y divide-gray-300">
              <thead>
                <tr>
                  <th scope="col" class="relative px-7 sm:w-12 sm:px-6">
                    <input
                      type="checkbox"
                      class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
                      :checked="indeterminate || selected.length === data.length"
                      :indeterminate="indeterminate"
                      @change="selected = $event.target.checked ? data.map((p) => p[identifier]) : []"
                    />
                  </th>
                  <DataTableHeader v-for="header in headers" :key="header.key" :header="header"/>
                  <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-3">
                    <span class="sr-only">Edit</span>
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 bg-white">
                <tr
                  v-for="entry in data"
                  :key="entry.email"
                  :class="[selected.includes(entry[identifier]) && 'bg-gray-50']"
                >
                  <td class="relative px-7 sm:w-12 sm:px-6">
                    <div
                      v-if="selected.includes(entry.email)"
                      class="absolute inset-y-0 left-0 w-0.5 bg-indigo-600"
                    ></div>
                    <input
                      type="checkbox"
                      class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
                      :value="entry[identifier]"
                      v-model="selected"
                    />
                  </td>
                  <DataTableCell
                    v-for="header in headers"
                    :key="header.key"
                    :header="header"
                    :entry="entry"
                    :selected="selected.includes(entry[identifier])"
                  />
                  <td class="whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                    <a href="#" class="text-indigo-600 hover:text-indigo-900">
                      Edit
                      <span class="sr-only">, {{ entry.name }}</span>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
  </div>
</template>

<script setup lang="ts" generic="T extends Record<string, any>">
import { ref, computed, PropType } from 'vue';
import EditButton from './EditButton.vue';
import { BulkOption, TableHeader } from './DataTableTypes';
import DataTableHeader from './DataTableHeader.vue';
import DataTableCell from '../DataTableCell.vue';

const props = defineProps({
  headers: {
    type: Array as PropType<TableHeader[]>,
    required: true,
  },

  data: {
    type: Array as PropType<T[]>,
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
  },
});

const selected = ref([] as unknown[]);
const indeterminate = computed(() => selected.value.length > 0 && selected.value.length < props.data.length);
</script>
