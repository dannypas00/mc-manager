<template>
  <ServerForm v-if="serverStore.model" :type="serverStore.model.type"/>

  <div class="w-full pt-4 flex justify-end">
    <PositiveButton class="text-center" :text="$t('general.buttons.save')" @click="save"/>
  </div>

  <FullpageSpinner
    v-if="loading"
    :reason="$t('pages.servers.create.save_loader', { server: serverStore.model.name })"
  />
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import ServerForm from './Form/ServerForm.vue';
import { useServerEditStore } from '../../Stores/Servers/ServerEditStore';
import PositiveButton from '../../Components/Buttons/PositiveButton.vue';
import FullpageSpinner from '../../Components/Layout/FullpageSpinner.vue';
import Server = App.Models.Server;

export default defineComponent({
  components: {
    FullpageSpinner,
    PositiveButton,
    ServerForm,
  },

  props: {
    server: {
      type: Object as PropType<Server>,
      required: true,
    },
  },

  data () {
    return {
      serverStore: useServerEditStore(),
    };
  },

  methods: {
    save () {
      console.log(this.serverStore.requestData);
    },
  },

  mounted () {
    this.serverStore.model = this.server;
    // TODO: THIS IS DEBUG DATA, REMOVE WHEN DONE TESTING
    this.serverStore.model.type = 2;
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
