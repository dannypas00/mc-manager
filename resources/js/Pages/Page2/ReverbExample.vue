<template>
  <PageHeader
    :title="$t('pages.page2.title')"
    :header="$t('pages.page2.title')"
  />

  <div>
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
        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
        placeholder="John Smith"
      />
    </div>
  </div>

  <div>
    <label
      for="email"
      class="block text-sm font-medium leading-6 text-gray-900"
      v-t="'pages.page2.form.email'"
    />
    <div class="mt-2">
      <input
        type="email"
        name="email"
        id="email"
        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
        placeholder="you@example.com"
      />
    </div>
  </div>

  <div>
    <label
      for="password"
      class="block text-sm font-medium leading-6 text-gray-900"
      v-t="'pages.page2.form.password'"
    />
    <div class="mt-2">
      <input
        type="password"
        name="password"
        id="password"
        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
      />
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import PageHeader from '../../Components/Layout/PageHeader.vue';
import { UserData } from '../../Types/generated';
import { UserUpdateRequest } from '../../Communication/Users/UserUpdateRequest';

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
      this.updateRequest.setData(this.userData)
        .setIdentifier()
    },
  },

  computed: {},
});
</script>
