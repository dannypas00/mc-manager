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
      }
    },
  },
});
</script>
