<template>
  <PageHeader
    :title="$t('pages.page2.title')"
    :header="$t('pages.page2.title')"
  />

  <form @submit.prevent="update">
    <label
      for="name"
      class="block text-sm font-medium leading-6 text-gray-900"
      v-t="'pages.page2.form.name'"
    />
    <div class="mt-2">
      <input
        type="text"
        name="name"
        id="name"
        class="mb-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
        placeholder="John Smith"
        v-model="userData.name"
      />
    </div>
    <button
      type="button"
      class="active:bg-brand-dark/80 rounded-md bg-brand px-2.5 py-1.5 text-sm font-semibold text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand"
      @click="update"
    >
      {{ $t('pages.page2.form.submit') }}
    </button>
  </form>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import PageHeader from '../../Components/Layout/PageHeader.vue';
import { UserData } from '../../Types/generated';
import { UserUpdateRequest } from '../../Communication/Users/UserUpdateRequest';
import { useAuthenticatedUserStore } from '../../Stores/AuthenticatedUserStore';

export default defineComponent({
  components: { PageHeader },

  data() {
    return {
      updateRequest: new UserUpdateRequest(),
      userData: {} as UserData,
    };
  },

  methods: {
    update() {
      this.updateRequest
        .setData(this.userData)
        .setIdentifier(this.userData.id)
        .getResponse();
    },
  },

  mounted() {
    this.userData = JSON.parse(
      JSON.stringify(useAuthenticatedUserStore().user)
    );
  },
});
</script>
