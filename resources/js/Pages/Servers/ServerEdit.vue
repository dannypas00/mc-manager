<template>
  <ServerForm v-if="serverStore.model" :type="serverStore.model.type" />

  <div class="flex w-full justify-end gap-2 pt-4">
    <PositiveButton
      class="text-center"
      :text="$t('general.buttons.save_and_continue_editing')"
      @click="save"
      outline
    />

    <PositiveButton
      class="text-center"
      :text="$t('general.buttons.save')"
      @click="
        async () => {
          await save();
          $inertia.get($route('servers.show', { id }));
        }
      "
    />
  </div>

  <FullpageSpinner
    v-if="serverStore.updateRequest.isLoading"
    :reason="
      $t('pages.servers.create.save_loader', { server: serverStore.model.name })
    "
  />
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import ServerForm from './Form/ServerForm.vue';
import { useServerEditStore } from '../../Stores/Servers/ServerEditStore';
import PositiveButton from '../../Components/Buttons/PositiveButton.vue';
import FullpageSpinner from '../../Components/Layout/FullpageSpinner.vue';

export default defineComponent({
  components: {
    FullpageSpinner,
    PositiveButton,
    ServerForm,
  },

  data() {
    return {
      id: this.$page.props.route_parameters.id as number,
      serverStore: useServerEditStore(),
    };
  },

  methods: {
    async save() {
      await this.serverStore.update();
    },
  },

  async mounted() {
    await this.serverStore.retrieve(this.id);
  },
});
</script>
