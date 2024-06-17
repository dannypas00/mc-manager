<template>
  <FormInput
    id="public-ip"
    v-model="serverStore.model.public_ip"
    placeholder="x.x.x.x or minecraft.example.com"
    :label="$t('pages.servers.form.public_ip.label')"
    :explanation="$t('pages.servers.form.public_ip.explanation')"
    warning="test"
  />

  <FormInput
    id="connection-ip"
    v-model="serverStore.model.local_ip"
    placeholder="x.x.x.x"
    :label="$t('pages.servers.form.local_ip.label')"
    :explanation="$t('pages.servers.form.local_ip.explanation')"
    warning="test"
  />

  <FormInput
    id="ssh-port"
    v-model="serverStore.model.ssh_port"
    placeholder="22"
    :label="$t('pages.servers.form.ssh_port.label')"
  />

  <!-- TODO: Test ssh connection button -->
  <div class="block">
    <NeutralButton class="inline-block" :text="$t('pages.servers.form.ssh_test_button')" @click="testSsh"/>
    <span v-if="sshConnectionStatus === true" class="inline-block text-green-400">
      <!-- Successful connection -->
      <FontAwesomeIcon icon="check"/>
    </span>
    <span v-else-if="sshConnectionStatus === false" class="inline-block text-red-500">
      <!-- Failed connection -->
      <FontAwesomeIcon icon="x"/>
    </span>
    <span v-else class="inline-block text-yellow-500">
      <!-- Unknown connection -->
      <FontAwesomeIcon icon="question-circle"/>
    </span>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import FormInput from '../../../Components/Form/FormInput.vue';
import { useServerEditStore } from '../../../Stores/Servers/ServerEditStore';
import NeutralButton from '../../../Components/Buttons/NeutralButton.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

export default defineComponent({
  components: { FontAwesomeIcon, NeutralButton, FormInput },

  data () {
    return {
      serverStore: useServerEditStore(),
      sshConnectionStatus: null as null|boolean,
    };
  },

  methods: {
    testSsh () {
      // TODO
      console.log('Test ssh!');
    },
  },
});
</script>
