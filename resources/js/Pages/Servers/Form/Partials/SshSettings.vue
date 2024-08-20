<template>
  <div>
    <span class="text-3xl" v-t="'pages.servers.form.ssh_separator'" />
    <hr />
  </div>

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
    required
  />

  <ConnectionTestButton
    :text="$t('pages.servers.form.ssh_test_button')"
    :test="testSsh"
  />
</template>

<script setup lang="ts">
import FormTextArea from '../../../../Components/Form/FormTextArea.vue';
import FormInput from '../../../../Components/Form/FormInput.vue';
import ConnectionTestButton from './ConnectionTestButton.vue';
import { useServerEditStore } from '../../../../Stores/Servers/ServerEditStore';
import axios from 'axios';

const serverStore = useServerEditStore();

async function testSsh(): Promise<boolean> {
  try {
    const response = await axios.post(
      route('web.api.servers.test.ssh') as string,
      {
        host: serverStore.model.local_ip,
        user: serverStore.model.ssh_username,
        port: Number(serverStore.model.ssh_port),
        private_key: serverStore.model.ssh_key,
      }
    );

    return response.status === 200;
  } catch {
    return false;
  }
}
</script>
