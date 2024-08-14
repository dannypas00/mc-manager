<template>
  <FormInput
    id="name"
    v-model="serverStore.model.name"
    :label="$t('pages.servers.form.name.label')"
    :placeholder="$t('pages.servers.form.name.placeholder')"
  />

  <FormTextArea
    id="description"
    v-model="serverStore.model.description"
    :label="$t('pages.servers.form.description.label')"
    :description="$t('pages.servers.form.description.description')"
    :placeholder="$t('pages.servers.form.description.placeholder')"
  />

  <FormProfileUpload
    id="icon"
    :label="$t('pages.servers.form.icon_upload.label')"
    :default-image-url="ApplicationLogo"
    @change="event => (serverStore.icon = event)"
  />
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import FormTextArea from '../../../Components/Form/FormTextArea.vue';
import FormInput from '../../../Components/Form/FormInput.vue';
import FormProfileUpload from '../../../Components/Form/FormProfileUpload.vue';
import { useServerEditStore } from '../../../Stores/Servers/ServerEditStore';
import ApplicationLogo from '../../../../images/icons/MCM-logo.webp';

export default defineComponent({
  components: {
    FormProfileUpload,
    FormInput,
    FormTextArea,
  },

  data() {
    return {
      serverStore: useServerEditStore(),
      ApplicationLogo,
    };
  },
});
</script>
