<template>
  <div class="flex min-h-full flex-1 flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <h2
        class="mt-6 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900"
        v-t="'pages.auth.login.header'"
      />
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[480px]">
      <div class="bg-white px-6 py-12 shadow sm:rounded-lg sm:px-12">
        <form class="space-y-6" @submit.prevent="login">
          <div>
            <label
              for="email"
              class="block text-sm font-medium leading-6 text-gray-900"
              v-t="'pages.auth.login.email.label'"
            />
            <div class="mt-2">
              <input
                v-model="form.email"
                id="email"
                name="email"
                type="email"
                autocomplete="email"
                required
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              />
              <span class="text-red-500 text-sm" v-if="$page.props.errors?.email">{{ $page.props.errors?.email }}</span>
            </div>
          </div>

          <div>
            <label
              for="password"
              class="block text-sm font-medium leading-6 text-gray-900"
              v-t="'pages.auth.login.password.label'"
            />
            <div class="mt-2">
              <input
                v-model="form.password"
                id="password"
                name="password"
                type="password"
                autocomplete="current-password"
                required
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              />
            </div>
          </div>

          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input
                v-model="form.rememberMe"
                id="remember-me"
                name="remember-me"
                type="checkbox"
                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
              />
              <label
                for="remember-me"
                class="ml-3 block text-sm leading-6 text-gray-900"
                v-t="'pages.auth.login.remember_me.label'"
              />
            </div>

            <div class="text-sm leading-6">
              <a
                class="font-semibold text-indigo-600 hover:text-indigo-500 text"
                @click="forgotPassword"
              >
                {{ $t('pages.auth.login.forgot_password') }}
              </a>
            </div>
          </div>

          <div>
            <button
              type="submit"
              class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
            >
              {{ $t('pages.auth.login.submit_button') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import AuthLayout from '../../Layouts/AuthLayout.vue';
import { router, useForm } from '@inertiajs/vue3';
import { UserData } from '../../Types/generated';

export default defineComponent({
  layout: AuthLayout,

  data () {
    return {
      form: useForm({
        email: '',
        password: '',
        rememberMe: false,
      }),
    };
  },

  methods: {
    login () {
      this.form.transform(data => ({
        ...data,
        rememberMe: data.rememberMe ? 'on' : null,
      })).post(route('login'));
    },

    forgotPassword () {
      // TODO
      console.log('To be implemented');
    },
  },
});
</script>
