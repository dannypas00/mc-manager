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
    id="port"
    v-model="serverStore.model.port"
    placeholder="25565"
    :label="$t('pages.servers.form.port.label')"
    type="number"
    :additional-props="{
      min: 0,
      max: 65535,
    }"
    required
  />

  <FormInput
    id="rcon-port"
    v-model="serverStore.model.rcon_port"
    placeholder="25575"
    :label="$t('pages.servers.form.rcon_port.label')"
    :explanation="$t('pages.servers.form.rcon_port.explanation')"
    type="number"
    :additional-props="{
      min: 0,
      max: 65535,
    }"
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

  <!-- When type is installed server, we only need ssh -->
  <template v-if="serverStore.model.type !== 2">
    <FtpSettings />
  </template>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import FormInput from '../../../Components/Form/FormInput.vue';
import { useServerEditStore } from '../../../Stores/Servers/ServerEditStore';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import FtpSettings from './Partials/FtpSettings.vue';
import SshSettings from './Partials/SshSettings.vue';

export default defineComponent({
  components: {
    SshSettings,
    FtpSettings,

    FontAwesomeIcon,
    FormInput,
  },

  data() {
    return {
      serverStore: useServerEditStore(),
    };
  },
});
</script>
