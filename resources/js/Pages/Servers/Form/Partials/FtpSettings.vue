<template>
  <div>
    <span class="text-3xl" v-t="'pages.servers.form.ftp_separator'" />
    <hr />
  </div>

  <ToggleButton
    v-model="serverStore.model.enable_ftp"
    :label="$t('pages.servers.form.enable_ftp.label')"
  />

  <div
    class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8"
    v-if="serverStore.model.enable_ftp"
  >
    <ToggleButton
      v-model="serverStore.model.is_sftp"
      :label="$t('pages.servers.form.is_sftp.label')"
    />

    <FormInput
      id="ftp-port"
      v-model="serverStore.model.ftp_port"
      placeholder="21"
      :label="$t('pages.servers.form.ftp_port.label')"
    />

    <FormInput
      id="ftp-username"
      v-model="serverStore.model.ftp_username"
      placeholder="myuser"
      :label="$t('pages.servers.form.ftp_username.label')"
      autocomplete="off"
    />

    <FormInput
      id="ftp-password"
      v-model="serverStore.model.ftp_password"
      :label="$t('pages.servers.form.ftp_password.label')"
      type="password"
      autocomplete="off"
    />

    <ConnectionTestButton
      :text="$t('pages.servers.form.ftp_test_button')"
      :test="testFtp"
    />
  </div>
</template>

<script setup lang="ts">
import FormInput from '../../../../Components/Form/FormInput.vue';
import ToggleButton from '../../../../Components/Form/ToggleButton.vue';
import ConnectionTestButton from './ConnectionTestButton.vue';
import { useServerEditStore } from '../../../../Stores/Servers/ServerEditStore';
import axios from 'axios';

const serverStore = useServerEditStore();

async function testFtp(): Promise<boolean> {
  try {
    const response = await axios.post(
      route('web.api.servers.test.ftp') as string,
      {
        host: serverStore.model.local_ip,
        port: Number(serverStore.model.ftp_port),
        user: serverStore.model.ftp_username,
        password: serverStore.model.ftp_password,
        is_sftp: serverStore.model.is_sftp,
      }
    );

    return response.status === 200;
  } catch {
    return false;
  }
}
</script>
