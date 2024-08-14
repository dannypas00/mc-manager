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

  <div>
    <span class="text-3xl" v-t="'pages.servers.form.ssh_separator'" />
    <hr>
  </div>

  <FormInput
    id="ssh-port"
    v-model="serverStore.model.ssh_port"
    placeholder="22"
    :label="$t('pages.servers.form.ssh_port.label')"
  />

  <FormInput
    id="ssh-username"
    v-model="serverStore.model.ssh_username"
    placeholder="myuser"
    :label="$t('pages.servers.form.ssh_username.label')"
    :explanation="$t('pages.servers.form.ssh_username.explanation')"
    autocomplete="off"
  />

  <FormTextArea
    id="ssh-private-key"
    v-model="serverStore.model.ssh_key"
    :label="$t('pages.servers.form.ssh_key.label')"
  />

  <ConnectionTestButton :text="$t('pages.servers.form.ssh_test_button')" :test="testSsh" />

  <div>
    <span class="text-3xl" v-t="'pages.servers.form.ftp_separator'" />
    <hr>
  </div>

  <ToggleButton v-model="serverStore.model.is_sftp" :label="$t('pages.servers.form.is_sftp.label')" />

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

  <ConnectionTestButton :text="$t('pages.servers.form.ftp_test_button')" :test="testFtp" />
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import FormInput from '../../../Components/Form/FormInput.vue';
import { useServerEditStore } from '../../../Stores/Servers/ServerEditStore';
import NeutralButton from '../../../Components/Buttons/NeutralButton.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import FormTextArea from '../../../Components/Form/FormTextArea.vue';
import axios from 'axios';
import ToggleButton from '../../../Components/Form/ToggleButton.vue';
import ConnectionTestButton from './Partials/ConnectionTestButton.vue';

export default defineComponent({
  components:
    {
      ConnectionTestButton,
      ToggleButton,
      FormTextArea,
      FontAwesomeIcon,
      NeutralButton,
      FormInput,
    },

  data () {
    return {
      serverStore: useServerEditStore(),
    };
  },

  methods: {
    async testSsh (): Promise<boolean> {
      try {
        const response = await axios.post(route('web.api.servers.test.ssh') as string, {
          host: this.serverStore.model.local_ip,
          user: this.serverStore.model.ssh_username,
          port: this.serverStore.model.ssh_port,
          private_key: this.serverStore.model.ssh_key,
        });

        return response.status === 200;
      } catch {
        return false;
      }
    },

    async testFtp (): Promise<boolean> {
      try {
        const response = await axios.post(route('web.api.servers.test.ftp') as string, {
          host: this.serverStore.model.local_ip,
          port: this.serverStore.model.port,
          user: this.serverStore.model.ftp_username,
          password: this.serverStore.model.ftp_password,
          is_sftp: this.serverStore.model.is_sftp,
        });

        return response.status === 200;
      } catch {
        return false;
      }
    },
  },

  mounted () {
    // TODO: THIS IS DEBUG DATA, REMOVE WHEN DONE TESTING
    this.serverStore.model.local_ip = '172.17.0.1';
    this.serverStore.model.ssh_username = 'mcm-test';
    this.serverStore.model.ssh_port = 2222;
    this.serverStore.model.ssh_key = '-----BEGIN OPENSSH PRIVATE KEY-----\n' +
      'b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAArAAAABNlY2RzYS\n' +
      '1zaGEyLW5pc3RwNTIxAAAACG5pc3RwNTIxAAAAhQQBdCb4l4oGSF5J5I+3pvI3zTPJyvWJ\n' +
      '5z+qdKLtZH7TR/Z+fzuXG3x2tH2A9tKhsZPxuBKPGrvpgzPArphunH+y7ucBjBwi4IF8pK\n' +
      'B82FwB9CPWWKWjikZF9nFtFt+gm4Mv32CjoqOmhPM1EHjavqt7zASx4VHCArZ40Aaua9Yg\n' +
      'OYgM0qIAAAEQ5EtpqORLaagAAAATZWNkc2Etc2hhMi1uaXN0cDUyMQAAAAhuaXN0cDUyMQ\n' +
      'AAAIUEAXQm+JeKBkheSeSPt6byN80zycr1iec/qnSi7WR+00f2fn87lxt8drR9gPbSobGT\n' +
      '8bgSjxq76YMzwK6Ybpx/su7nAYwcIuCBfKSgfNhcAfQj1lilo4pGRfZxbRbfoJuDL99go6\n' +
      'KjpoTzNRB42r6re8wEseFRwgK2eNAGrmvWIDmIDNKiAAAAQgEbg4CwdXC+584UjT5BF8c7\n' +
      'dgAfB/ZqOw2RSEx+AOGosli7YwoponJ4NiiEm4M2NRHqQYmsPq78UdiY03rKwWG8UAAAAB\n' +
      'Fyb290QDYwZTZmNTUyNGU5NQE=\n' +
      '-----END OPENSSH PRIVATE KEY-----\n';
  },
});
</script>
