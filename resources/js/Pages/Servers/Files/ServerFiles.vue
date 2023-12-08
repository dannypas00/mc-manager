<template>
  <div class="px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-base font-semibold leading-6 text-gray-900">
          <a
            class="cursor-pointer hover:underline active:text-indigo-400"
            :href="$route('servers.files', { id: serverStore.model.id })"
          >
            / minecraft
          </a>

          <template v-for="(directory, index) in splitPath" :key="index">
            /
            <a
              v-if="!openedFile"
              class="cursor-pointer hover:underline active:text-indigo-400"
              :href="$route('servers.files', {id: serverStore.model.id, path: take(splitPath, index + 1).join('/') })"
            >
              {{ directory }}
            </a>

            <span v-else>
              {{ directory }}
            </span>
          </template>
        </h1>
      </div>
      <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <!-- New file button -->
        <button
          v-if="!openedFile"
          type="button"
          class="block rounded-md bg-indigo-600 px-3 py-1.5 text-center text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
        >
          {{ $t('pages.servers.show.files.new_file') }}
        </button>

        <!-- Save button -->
        <button
          v-else
          type="button"
          class="block rounded-md bg-green-600 px-3 py-1.5 text-center text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
          @click="$refs.editor.save"
        >
          {{ $t('pages.servers.show.files.save') }}
        </button>
      </div>
    </div>

    <!-- File list -->
    <div class="mt-2 flow-root">
      <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
          <div class="relative">
            <template v-if="openedFile !== null">
              <Suspense>
                <ServerFileEditor ref="editor" :file="openedFile"/>

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
                  @click="deleteFiles"
                >
                  {{ $t('pages.servers.show.files.bulk_delete', { n: selectedFiles.length }) }}
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

  <ConfirmationDialog
    v-model:open="confirmDeletionOpen"
    :title="$t('pages.servers.show.files.bulk_delete_confirm.title')"
    :message="$t('pages.servers.show.files.bulk_delete_confirm.message')"
    :positive-button-text="$t('pages.servers.show.files.bulk_delete_confirm.positive')"
    @positive="onDeleteConfirm"
  />
</template>

<script lang="ts">
import { defineAsyncComponent, defineComponent } from 'vue';
import { StorageListingRequest } from '../../../Communications/McManager/Storage/StorageListingRequest';
import { useServerShowStore } from '../../../Stores/Servers/ServerShowStore';
import ServerFileList from './Components/ServerFileList.vue';
import { FileEntry } from '../../../Types/FileEntry';
import _, { take } from 'lodash';
import ServerShowTemplate from '../ServerShowTemplate.vue';
import ConfirmationDialog from '../../../Components/Popups/ConfirmationDialog.vue';
import { StorageDeleteRequest } from '../../../Communications/McManager/Storage/StorageDeleteRequest';
import { useToast } from 'vue-toastification';

export default defineComponent({
  components: {
    ConfirmationDialog,
    ServerFileEditor: defineAsyncComponent(() => import('./Components/ServerFileEditor.vue')),
    ServerFileList,
  },

  layout: ServerShowTemplate,

  data () {
    return {
      serverStore: useServerShowStore(),
      storageListingRequest: new StorageListingRequest(),
      storageDeleteRequest: new StorageDeleteRequest(),
      data: [] as FileEntry[],
      selectedFiles: [] as FileEntry[],
      path: this.$attrs.route_parameters?.path ?? '',
      openedFile: null as null | FileEntry,
      confirmDeletionOpen: false,
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
          if (response.data.directories) {
            this.data = response.data.directories;
            return;
          }

          if (response.data.file) {
            this.openFile({ path: response.data.file } as FileEntry);
          }
        });
    },

    openFile (file: FileEntry) {
      this.openedFile = file;
    },

    deleteFiles () {
      // If file list includes at least one directory, request confirmation
      const directories = this.selectedFiles.filter(file => file.type === 'dir');
      if (directories.length > 0) {
        this.confirmDeletionOpen = true;
        return;
      }

      this.onDeleteConfirm();
    },

    onDeleteConfirm () {
      console.log(_.map(this.selectedFiles, 'path'));
      this.storageDeleteRequest
        .setId(this.serverStore.model.id)
        .setPaths(_.map(this.selectedFiles, 'path'))
        .getResponse()
        .then(() => {
          useToast().success(this.$t('pages.servers.show.files.bulk_delete_success_toast', { n: this.selectedFiles.length }));
          this.selectedFiles = [];
          this.getDir();
        });
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
