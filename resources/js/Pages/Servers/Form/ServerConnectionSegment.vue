<template>
  <FormInput
    id="public-ip"
    v-model="serverStore.model.public_ip"
    placeholder="x.x.x.x or minecraft.example.com"
    :label="$t('pages.servers.form.public_ip.label')"
    :explanation="$t('pages.servers.form.public_ip.explanation')"
    warning="test"
    required
  />

  <FormInput
    id="connection-ip"
    v-model="serverStore.model.local_ip"
    placeholder="x.x.x.x"
    :label="$t('pages.servers.form.local_ip.label')"
    :explanation="$t('pages.servers.form.local_ip.explanation')"
    warning="test"
    required
  />

  <SshSettings />

  <FtpSettings />
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import FormInput from '../../../Components/Form/FormInput.vue';
import { useServerEditStore } from '../../../Stores/Servers/ServerEditStore';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import FtpSettings from './Partials/FtpSettings.vue';
import SshSettings from './Partials/SshSettings.vue';

export default defineComponent({
  components:
    {
      SshSettings,
      FtpSettings,

      FontAwesomeIcon,
      FormInput,
    },

  data () {
    return {
      serverStore: useServerEditStore(),
    };
  },
});
</script>
