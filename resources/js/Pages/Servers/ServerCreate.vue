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

  <ServerForm :type="FormType.Create"/>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import PageTitle from '../../Components/Layout/PageTitle.vue';
import { ArrowLeftIcon } from '@heroicons/vue/24/solid';
import NeutralButton from '../../Components/Buttons/NeutralButton.vue';
import ServerForm from './Form/ServerForm.vue';
import { FormType } from '../../Enums/FormType';

/**
 * TODO:
 * - Name
 * - IPs
 * - Ports
 * - Installed software
 */

export default defineComponent({
  components: {
    ServerForm,
    PageTitle,
    ArrowLeftIcon,
    NeutralButton,
  },

  data () {
    return {};
  },

  methods: {},

  computed: {
    FormType () {
      return FormType;
    },
  },
});
</script>
