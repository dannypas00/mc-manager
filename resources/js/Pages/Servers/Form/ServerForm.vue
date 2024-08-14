<template>
  <SegmentedPanel :segments="getSegments()">
    <template #segment-info>
      <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8">
        <ServerInfoSegment />
      </div>
    </template>

    <template #segment-managed>
      <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8">
        <ServerManagedSegment />
      </div>
    </template>

    <template #segment-connection>
      <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8">
        <ServerConnectionSegment />
      </div>
    </template>

    <template #segment-minecraft>
      <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8">
        <ServerMinecraftSegment />
      </div>
    </template>
  </SegmentedPanel>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import { FormType } from '../../../Enums/FormType';
import { PanelSegment } from '../../../Types/Components/PanelSegment';
import ServerInfoSegment from './ServerInfoSegment.vue';
import SegmentedPanel from '../../../Components/Layout/SegmentedPanel.vue';
import { useServerEditStore } from '../../../Stores/Servers/ServerEditStore';
import ServerManagedSegment from './ServerManagedSegment.vue';
import ServerConnectionSegment from './ServerConnectionSegment.vue';
import ServerMinecraftSegment from './ServerMinecraftSegment.vue';

export default defineComponent({
  components: {
    ServerMinecraftSegment,
    ServerConnectionSegment,
    ServerManagedSegment,
    SegmentedPanel,
    ServerInfoSegment,
  },

  options: {
    type: {
      type: Number as PropType<FormType>,
      required: true,
    },
  },

  data() {
    return {
      serverStore: useServerEditStore(),
    };
  },

  methods: {
    getSegments(): PanelSegment[] {
      // Info segment
      const infoSegment = {
        title: this.$t('pages.servers.form.info_segment.title'),
        description: this.$t('pages.servers.form.info_segment.description'),
        slotName: 'info',
      };

      // Managed segment
      const managedSegment = {
        title: this.$t('pages.servers.form.managed_segment.title'),
        description: this.$t('pages.servers.form.managed_segment.description'),
        slotName: 'managed',
      };

      // Connection segment
      // TODO: IP address, host, SSH key, ftp creds
      const connectionSegment = {
        title: this.$t('pages.servers.form.connection_segment.title'),
        description: this.$t(
          'pages.servers.form.connection_segment.description'
        ),
        slotName: 'connection',
      };

      // Minecraft segment
      // TODO: Version, modpack / server type
      const minecraftSegment = {
        title: this.$t('pages.servers.form.minecraft_segment.title'),
        description: this.$t(
          'pages.servers.form.minecraft_segment.description'
        ),
        slotName: 'minecraft',
      };

      switch (this.serverStore.model.type) {
        case 1:
          return [infoSegment, connectionSegment];
        case 2:
          return [infoSegment, connectionSegment, minecraftSegment];
        case 3:
          return [infoSegment, managedSegment, minecraftSegment];
        default:
          // Server didn't load yet
          return [];
      }
    },
  },

  mounted() {
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
