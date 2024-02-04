<template>
  <PageTitle :title="$t('pages.servers.create.title')">
    <NeutralButton
      :title="$t('global.tips.back')"
      :href="$route('servers.index')"
      class="h-full"
    >
      <ArrowLeftIcon class="h-full"/>
    </NeutralButton>
  </PageTitle>

  <ServerForm v-if="serverStore.model.type" :type="FormType.Create"/>

  <ServerTypeSelector v-else @save="onTypeSelect"/>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import PageTitle from '../../Components/Layout/PageTitle.vue';
import { ArrowLeftIcon } from '@heroicons/vue/24/solid';
import NeutralButton from '../../Components/Buttons/NeutralButton.vue';
import ServerForm from './Form/ServerForm.vue';
import { FormType } from '../../Enums/FormType';
import { useServerEditStore } from '../../Stores/Servers/ServerEditStore';
import ServerTypeSelector from './Form/ServerTypeSelector.vue';
import ServerType = App.Models.ServerType;

/**
 * TODO:
 * - Name
 * - IPs
 * - Ports
 * - Installed software
 */

export default defineComponent({
  components: {
    ServerTypeSelector,
    ServerForm,
    PageTitle,
    ArrowLeftIcon,
    NeutralButton,
  },

  data () {
    return {
      serverStore: useServerEditStore(),
    };
  },

  methods: {
    onTypeSelect (type: ServerType) {
      this.serverStore.model.type = type;
    },
  },

  computed: {
    FormType () {
      return FormType;
    },
  },

  mounted () {
    this.serverStore.setEmpty();

    // TODO: Remove because debugging
    this.serverStore.model.type = 2;
  },
});
</script>
