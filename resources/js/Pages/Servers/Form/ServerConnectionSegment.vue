<template>
  <FormInput
    id="connection-ip"
    v-model="serverStore.model.local_ip"
    placeholder="x.x.x.x"
    :label="$t('pages.servers.form.local_ip.label')"
    :explanation="$t('pages.servers.form.local_ip.explanation')"
    warning="test"
  />
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import FormInput from '../../../Components/Form/FormInput.vue';
import { useServerEditStore } from '../../../Stores/Servers/ServerEditStore';

export default defineComponent({
  components: { FormInput },

  data () {
    return {
      serverStore: useServerEditStore(),
    };
  },

  methods: {},

  computed: {},
});
</script>
