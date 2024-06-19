<template>
  <PageTitle :title="$t('pages.servers.create.title')">
    <NeutralButton
      :title="$t('global.tips.back')"
      :href="$route('servers.index')"
      class="h-full"
    >
      <ArrowLeftIcon class="h-full" />
    </NeutralButton>
  </PageTitle>

  <ServerTypeSelector @save="onTypeSelect" />
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
import { router } from '@inertiajs/vue3';
import ServerType = App.Models.ServerType;
import { useUserStore } from '../../Stores/UserStore';

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
    PageTitle,
    ArrowLeftIcon,
    NeutralButton,
  },

  data() {
    return {
      serverStore: useServerEditStore(),
      userStore: useUserStore(),
    };
  },

  methods: {
    onTypeSelect(type: ServerType) {
      router.post(route('servers.create'), {
        name: `${this.userStore.user.name}'s server`,
        type,
      });
    },
  },

  computed: {
    FormType() {
      return FormType;
    },
  },

  mounted() {
    this.serverStore.setEmpty();

    // TODO: Remove because debugging
    this.serverStore.model.type = 2;
    this.onTypeSelect(2);
  },
});
</script>
