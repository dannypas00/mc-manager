<template>
  <div>
    <span class="text-3xl" v-t="'pages.servers.form.ssh_separator'" />
    <hr />
  </div>

  <!--<ToggleButton-->
  <!--  v-model="serverStore.model.enable_ssh"-->
  <!--  :label="$t('pages.servers.form.enable_ssh.label')"-->
  <!--/>-->

  <!--v-if="serverStore.model.enable_ssh"-->
  <div
    class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8"
  >
    <FormInput
      id="ssh-port"
      v-model="serverStore.model.ssh_port"
      placeholder="22"
      :label="$t('pages.servers.form.ssh_port.label')"
      required
    />

    <FormInput
      id="ssh-username"
      v-model="serverStore.model.ssh_username"
      placeholder="myuser"
      :label="$t('pages.servers.form.ssh_username.label')"
      :explanation="$t('pages.servers.form.ssh_username.explanation')"
      autocomplete="off"
      required
    />

    <FormTextArea
      id="ssh-private-key"
      v-model="serverStore.model.ssh_key"
      :label="$t('pages.servers.form.ssh_key.label')"
      :placeholder="
        serverStore.model.is_ssh_key_filled
          ? $t('pages.servers.form.ssh_key.placeholder')
          : '-----BEGIN OPENSSH PRIVATE KEY-----\n<...>\n-----END OPENSSH PRIVATE KEY-----'
      "
      :required="!serverStore.model.is_ssh_key_filled"
    />

    <ConnectionTestButton
      v-if="
        serverStore.model.local_ip &&
        serverStore.model.ssh_username &&
        serverStore.model.ssh_port &&
        serverStore.model.ssh_key
      "
      :text="$t('pages.servers.form.ssh_test_button')"
      :test="testSsh"
    />
  </div>
</template>

<script setup lang="ts">
import FormTextArea from '../../../../Components/Form/FormTextArea.vue';
import FormInput from '../../../../Components/Form/FormInput.vue';
import ConnectionTestButton from './ConnectionTestButton.vue';
import { useServerEditStore } from '../../../../Stores/Servers/ServerEditStore';
import axios from 'axios';
import ToggleButton from '../../../../Components/Form/ToggleButton.vue';

const serverStore = useServerEditStore();

async function testSsh(): Promise<boolean> {
  try {
    const response = await axios.post(route('api.servers.test.ssh') as string, {
      host: serverStore.model.local_ip,
      user: serverStore.model.ssh_username,
      port: Number(serverStore.model.ssh_port),
      private_key: serverStore.model.ssh_key,
    });

    return response.status === 200;
  } catch (e) {
    console.log(e);
    return false;
  }
}
</script>
