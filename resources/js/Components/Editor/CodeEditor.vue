<template>
  <div ref="editor" class="overflow-hidden w-full h-[40vw]"/>
</template>

<script lang="ts">
import * as monaco from 'monaco-editor/esm/vs/editor/editor.api';
import { editor } from 'monaco-editor/esm/vs/editor/editor.api';
import { defineComponent, unref } from 'vue';
import IStandaloneEditorConstructionOptions = editor.IStandaloneEditorConstructionOptions;
import IModelContentChangedEvent = editor.IModelContentChangedEvent;
import IStandaloneCodeEditor = editor.IStandaloneCodeEditor;

export default defineComponent({
  emits: ['update:model-value'],

  props: {
    value: {
      type: String,
      required: true,
    },

    language: {
      type: String,
      required: false,
      default: '',
    },
  },

  data () {
    return {
      editor: null as null | IStandaloneCodeEditor,
    };
  },

  methods: {
    getValue (): string {
      return this.editor?.getValue() as string;
    },
  },

  mounted () {
    this.editor = monaco.editor.create(this.$refs.editor as HTMLElement, {
      value: 'test',
      // TODO: Dynamic language detection
      language: 'typescript',
      theme: 'vs-dark',
    } as IStandaloneEditorConstructionOptions);
  },
});
</script>
