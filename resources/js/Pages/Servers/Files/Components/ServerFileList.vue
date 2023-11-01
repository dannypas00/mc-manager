<template>
  <table class="min-w-full table-fixed divide-y divide-gray-300">
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
        :class="[selected.includes(entry) && 'bg-gray-50']"
      >
        <td class="relative px-7 sm:w-12 sm:px-6">
          <div
            v-if="selected.includes(entry)"
            class="absolute inset-y-0 left-0 w-0.5 bg-indigo-600"
          />
          <input
            v-model="selected"
            type="checkbox"
            class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
            :value="entry"
          >
        </td>
        <td
          :class="[
            'whitespace-nowrap py-4 pr-3 text-sm font-medium',
            selected.includes(entry.path) ? 'text-indigo-600' : 'text-gray-900'
          ]"
        >
          <a class="cursor-pointer hover:underline active:text-indigo-400" @click="() => onEntryClick(entry)">
            {{ entry.path + (entry.type === 'dir' ? '/' : '') }}
          </a>
        </td>
        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400 text-right">
          {{ entry.file_size > 0 ? humanFileSize(entry.file_size) : '' }}
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import _ from 'lodash';
import { FileEntry } from '../../../../Types/FileEntry';
import humanFileSize from '../../../../Utils/HumanFileSize';

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
    humanFileSize,

    onEntryClick (entry: FileEntry) {
      if (entry.type === 'dir') {
        this.$emit('go-to-dir', entry.path);
        return;
      }

      this.$emit('open-file', entry);
    },
  },

  computed: {
    sortedEntries (): FileEntry[] {
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
  },
});
</script>
