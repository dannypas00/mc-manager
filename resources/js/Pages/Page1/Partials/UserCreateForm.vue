<template>
  <form @submit.prevent="onSubmit" class="text-left">
    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
      <div class="col-span-6">
        <label
          for="name"
          class="block text-sm font-medium leading-6 text-gray-900"
        >
          {{ $t('pages.page1.user_form.name') }}
        </label>
        <div class="mt-2">
          <input
            v-model="form.name"
            type="text"
            name="name"
            id="name"
            autocomplete="given-name"
            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
          />
          <span v-if="$page.props.errors?.name" class="text-sm text-red-500">
            {{ $page.props.errors?.names }}
          </span>
        </div>

        <div class="col-span-6">
          <label
            for="email"
            class="block text-sm font-medium leading-6 text-gray-900"
          >
            {{ $t('pages.page1.user_form.email') }}
          </label>
          <div class="mt-2">
            <input
              v-model="form.email"
              type="email"
              name="email"
              id="email"
              autocomplete="email"
              class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
            />
            <span v-if="$page.props.errors?.email" class="text-sm text-red-500">
              {{ $page.props.errors?.email }}
            </span>
          </div>
        </div>

        <div class="col-span-6">
          <label
            for="password"
            class="block text-sm font-medium leading-6 text-gray-900"
          >
            {{ $t('pages.page1.user_form.password') }}
          </label>
          <div class="mt-2">
            <input
              v-model="form.password"
              type="password"
              name="password"
              id="password"
              autocomplete="new-password"
              class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
            />
          </div>
        </div>

        <div class="col-span-6">
          <label
            for="password_confirmation"
            class="block text-sm font-medium leading-6 text-gray-900"
          >
            {{ $t('pages.page1.user_form.password_confirmation') }}
          </label>
          <div class="mt-2">
            <input
              v-model="form.password_confirmation"
              type="password"
              name="password_confirmation"
              id="password_confirmation"
              autocomplete="new-password"
              class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
            />
            <span
              v-if="$page.props.errors?.password"
              class="text-sm text-red-500"
            >
              {{ $page.props.errors?.password }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <div
      class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3"
    >
      <button
        v-t="'pages.page1.user_form.submit'"
        type="submit"
        class="inline-flex w-full justify-center rounded-md bg-brand px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-hover focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand sm:col-start-2"
      />
      <button
        v-t="'general.cancel'"
        type="button"
        class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:col-start-1 sm:mt-0"
        @click="() => emit('cancel')"
      />
    </div>
  </form>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { UserCreateData } from '../../../Communication/Users/UserCreateRequest';

const emit = defineEmits(['submit', 'cancel']);

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms: true,
} as UserCreateData);

function onSubmit() {
  emit('submit', form.data());
}
</script>
