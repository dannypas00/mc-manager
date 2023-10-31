<template>
  <div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-base font-semibold leading-6 text-gray-900">
          <a
            class="cursor-pointer hover:underline active:text-indigo-400"
            @click="path = ''"
          >
            / minecraft
          </a>

          <template v-for="(directory, index) in splitPath">
            /
            <a
              class="cursor-pointer hover:underline active:text-indigo-400"
              @click="path = take(splitPath, index + 1).join('/')"
            >
              {{ directory }}
            </a>
          </template>
        </h1>
      </div>
      <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <button
          v-if="!openedFile"
          type="button"
          class="block rounded-md bg-indigo-600 px-3 py-1.5 text-center text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
        >
          {{ $t('pages.servers.show.files.new_file') }}
        </button>

        <button
          v-else
          type="button"
          class="block rounded-md bg-green-600 px-3 py-1.5 text-center text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
        >
          {{ $t('pages.servers.show.files.save') }}
        </button>
      </div>
    </div>
    <div class="mt-2 flow-root">
      <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
          <div class="relative">

            <template v-if="openedFile !== null">
              <Suspense>
                <ServerFileEditor
                  :file="openedFile"
                  class="h-[70vh]"
                />

                <template #fallback>
                  Loading editor...
                </template>
              </Suspense>
            </template>

            <template v-else>
              <div
                v-if="selectedFiles.length > 0"
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
              <input
                type="checkbox"
                class="ms-4 my-4 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
                :checked="indeterminate || selectedFiles.length === data.length"
                :indeterminate="indeterminate"
                @change="selectedFiles = $event.target.checked ? data : []"
              >

              <hr class="divide-y">

              <ServerFileList
                v-model:selected-files="selectedFiles"
                :entries="data"
                :is-root="['', '/'].includes(path)"
                @go-to-dir="path = $event"
                @go-up="goUp"
                @open-file="openFile"
              />
            </template>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineAsyncComponent, defineComponent } from 'vue';
import { StorageListingRequest } from '../../../Communications/McManager/Storage/StorageListingRequest';
import { useServerShowStore } from '../../../Stores/Servers/ServerShowStore';
import ServerFileList from '../Files/ServerFileList.vue';
import { FileEntry } from '../../../Types/FileEntry';
import { take } from 'lodash';

export default defineComponent({
  components: {
    ServerFileEditor: defineAsyncComponent(() => import('../Files/ServerFileEditor.vue')),
    ServerFileList,
  },

  data () {
    return {
      serverStore: useServerShowStore(),
      storageListingRequest: new StorageListingRequest(),
      data: [] as FileEntry[],
      selectedFiles: [] as FileEntry[],
      path: '',
      openedFile: null as null | FileEntry,
    };
  },

  methods: {
    take,

    goUp () {
      this.path = this.path.replace(/\/?[^\/]*$/, '');
    },

    getDir () {
      this.storageListingRequest
        .setPath(this.path)
        .setServerId(this.serverStore.model.id)
        .getResponse()
        .then(response => {
          this.data = [];
          this.data = response.data;
        });
    },

    openFile (file: FileEntry) {
      this.openedFile = file;
    },
  },

  computed: {
    indeterminate () {
      return this.selectedFiles.length > 0 && this.selectedFiles.length < this.data.length;
    },

    splitPath () {
      return (this.openedFile?.path ?? this.path).split('/').filter(path => path !== '') ?? [];
    },
  },

  watch: {
    path (newValue, oldValue) {
      if (newValue !== oldValue) {
        this.getDir();
      }
    },
  },

  mounted () {
    this.getDir();
  },
});
</script>
