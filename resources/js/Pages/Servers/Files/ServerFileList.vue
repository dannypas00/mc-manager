<template>
  <div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-base font-semibold leading-6 text-gray-900">
          Users
        </h1>
      </div>
      <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <button
          type="button"
          class="block rounded-md bg-indigo-600 px-3 py-1.5 text-center text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
        >
          New file
        </button>
      </div>
    </div>
    <div class="mt-2 flow-root">
      <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
          <div class="relative">
            <div
              v-if="selected.length > 0"
              class="absolute left-14 top-0 flex h-12 items-center space-x-3 sm:left-12"
            >
              <button
                type="button"
                class="inline-flex items-center rounded px-2 py-1 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-30 disabled:hover:bg-white"
              >
                Bulk edit
              </button>
              <button
                type="button"
                class="inline-flex items-center rounded px-2 py-1 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-30 disabled:hover:bg-white"
              >
                Delete all
              </button>
            </div>
            <table class="min-w-full table-fixed divide-y divide-gray-300">
              <thead>
                <tr>
                  <th scope="col" class="relative px-7 py-5 sm:w-12 sm:px-6">
                    <input
                      type="checkbox"
                      class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
                      :checked="indeterminate || selected.length === entries.length"
                      :indeterminate="indeterminate"
                      @change="selected = $event.target.checked ? entries.map((entry) => entry.path) : []"
                    >
                  </th>
                  <th scope="col" class="min-w-[12rem] py-3.5 pr-3 text-left text-sm font-semibold text-gray-900">
                  </th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                  </th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                  </th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                  </th>
                  <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-3">
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <tr v-if="!isRoot">
                  <td class="relative px-7 sm:w-12 sm:px-6">
                    <div
                      class="-mt-2 h-4 w-4"
                    />
                  </td>
                  <td :class="['whitespace-nowrap py-4 pr-3 text-sm font-medium text-gray-900']">
                    <a
                      class="cursor-pointer hover:underline active:text-indigo-400"
                      @click="() => $emit('go-up')"
                    >
                      ../
                    </a>
                  </td>
                </tr>
                <tr
                  v-for="entry in sortedEntries"
                  :key="entry.path"
                  :class="[selected.includes(entry.path) && 'bg-gray-50']"
                >
                  <td class="relative px-7 sm:w-12 sm:px-6">
                    <div
                      v-if="selected.includes(entry.path)"
                      class="absolute inset-y-0 left-0 w-0.5 bg-indigo-600"
                    />
                    <input
                      v-model="selected"
                      type="checkbox"
                      class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
                      :value="entry.path"
                    >
                  </td>
                  <td :class="['whitespace-nowrap py-4 pr-3 text-sm font-medium', selected.includes(entry.path) ? 'text-indigo-600' : 'text-gray-900']">
                    <a class="cursor-pointer hover:underline active:text-indigo-400" @click="() => onEntryClick(entry)">{{
                        entry.path + (entry.type === 'dir' ? '/' : '')
                                                                                                                        }}</a>
                  </td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                    {{ entry.type }}
                  </td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                    {{ entry.visibility }}
                  </td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                    {{ entry.extra_metadata }}
                  </td>
                  <td class="whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                    <a v-if="entry.type === 'file'" href="#" class="text-indigo-600 hover:text-indigo-900">
                      Edit<span class="sr-only">, {{ entry.path }}</span>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import _ from 'lodash';
import { FileEntry } from '../../../Types/StorageFileEntry';

export default defineComponent({
  emits: [
    'update:selected-files',
    'go-to-dir',
    'go-up',
    'open-file',
  ],

  components: {},

  props: {
    entries: {
      type: Array as PropType<FileEntry[]>,
      required: true,
    },

    selectedFiles: {
      type: Array as PropType<FileEntry[]>,
      required: true,
    },

    isRoot: {
      type: Boolean,
      required: true,
    },
  },

  data () {
    return {};
  },

  methods: {
    onEntryClick (entry: FileEntry) {
      if (entry.type === 'dir') {
        this.$emit('go-to-dir', entry.path);
        return;
      }

      this.$emit('open-file', entry);
    },
  },

  computed: {
    sortedEntries () {
      return _.concat(this.sortedDirectories, this.sortedFiles);
    },

    sortedDirectories (): FileEntry[] {
      const directories = _.sortBy(_.filter(this.entries, entry => entry.type === 'dir'), 'path');
      return directories;
    },

    sortedFiles () {
      return _.sortBy(_.filter(this.entries, entry => entry.type === 'file'), 'path');
    },

    selected: {
      get () {
        return this.selectedFiles;
      },
      set (value) {
        this.$emit('update:selected-files', value);
      },
    },

    indeterminate () {
      return this.selected.length > 0 && this.selected.length < this.entries.length;
    },
  },
});
</script>
