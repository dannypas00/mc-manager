<template>
  <div ref="editor" class="w-full h-full"/>
</template>

<script lang="ts">
import * as monaco from 'monaco-editor/esm/vs/editor/editor.api';
import { editor } from 'monaco-editor/esm/vs/editor/editor.api';
import { defineComponent } from 'vue';
import IStandaloneEditorConstructionOptions = editor.IStandaloneEditorConstructionOptions;
import IModelContentChangedEvent = editor.IModelContentChangedEvent;
import IStandaloneCodeEditor = editor.IStandaloneCodeEditor;

export default defineComponent({
  emits: ['update:model-value'],

  props: {
    modelValue: {
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

  mounted () {
    this.editor = monaco.editor.create(this.$refs.editor as HTMLElement, {
      value: this.modelValue,
      language: 'typescript',
      theme: 'vs-dark',
    } as IStandaloneEditorConstructionOptions);

    this.editor.onDidChangeModelContent((event: IModelContentChangedEvent) => {
      this.$emit('update:model-value', this.editor?.getModel()?.getValue());
    });
  },
});
</script>

