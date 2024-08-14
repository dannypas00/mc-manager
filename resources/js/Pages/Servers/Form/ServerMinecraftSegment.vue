<template>
  <!--
    Version
    Custom jar upload
    server.properties customization
    Restart schedule (For later)
    Backup schedule (For later)
   -->
  <ToggleButton
    v-model="serverStore.customizeInstallProcess"
    :label="$t('pages.servers.form.customize_install.label')"
  />
  <div
    v-if="serverStore.customizeInstallProcess"
    class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8"
  >
    <div class="grid grid-cols-1 gap-y-2">
      <span v-t="'pages.servers.form.upload_custom_jar_explanation'" />
      <NeutralButton
        :text="
          customJar?.name ?? $t('pages.servers.form.upload_custom_jar_button')
        "
        @click="uploadDialog.open"
      />
    </div>
    <FormInput
      id="custom-docker-image"
      v-model="serverStore.dockerImage"
      :label="$t('pages.servers.form.custom_docker_image.label')"
      :explanation="$t('pages.servers.form.custom_docker_image.explanation')"
    />
    <!-- TODO: Custom start script -->
  </div>
  <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8" v-else>
    <ComboboxInput
      :options="versionOptions"
      v-model="serverStore.model.version"
      required
      :label="$t('pages.servers.form.version.label')"
    />
  </div>

  <div class="h-[40vh] w-full">
    <span v-t="'pages.servers.form.server_properties.label'" />
    <VueMonacoEditor
      theme="vs-dark"
      :options="monacoOptions"
      language="TOML"
      v-model:value="serverStore.serverProperties"
    />
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import ToggleButton from '../../../Components/Form/ToggleButton.vue';
import ComboboxInput from '../../../Components/Form/ComboboxInput.vue';
import { useServerEditStore } from '../../../Stores/Servers/ServerEditStore';
import NeutralButton from '../../../Components/Buttons/NeutralButton.vue';
import { useFileDialog } from '@vueuse/core';
import VueMonacoEditor from '@guolao/vue-monaco-editor';
import FormInput from '../../../Components/Form/FormInput.vue';

export default defineComponent({
  components: {
    FormInput,
    VueMonacoEditor,
    NeutralButton,
    ComboboxInput,
    ToggleButton,
  },

  data() {
    return {
      serverStore: useServerEditStore(),
      versionOptions: ['1.21', '1.20', '1.19', '1.18'],
      uploadDialog: useFileDialog({
        accept: 'application/java-archive',
        directory: false,
        multiple: false,
      }),
      customJar: undefined as undefined | File,
      monacoOptions: {
        automaticLayout: true,
        formatOnType: true,
        formatOnPaste: true,
      },
    };
  },

  watch: {
    'uploadDialog.files'(files: FileList) {
      this.customJar = files[0];
    },
  },
});
</script>
