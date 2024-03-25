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
        <div class="border-b border-gray-200">
          <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <a
              v-for="(tab, mode) in tabs"
              class="cursor-pointer select-none"
              :key="tab.name"
              :class="[selectedMode === mode ? 'border-brand-hover text-brand' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700', 'whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium']"
              :aria-current="selectedMode === mode ? 'page' : undefined"
              @click="() => changeMode(mode)"
            >
              {{ tab.name }}
            </a>
          </nav>
        </div>

        <form class="space-y-6 mt-4" @submit.prevent="() => selectedMode === 'login' ? login() : register()">
          <div v-if="selectedMode === 'register'">
            <label
              for="name"
              class="block text-sm font-medium leading-6 text-gray-900"
              v-t="'pages.auth.login.name.label'"
            />
            <div class="mt-2">
              <input
                v-model="form.name"
                id="name"
                name="name"
                type="text"
                autocomplete="username"
                required
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand sm:text-sm sm:leading-6"
              />
              <span class="text-red-500 text-sm" v-if="$page.props.errors?.name">{{ $page.props.errors?.name }}</span>
            </div>
          </div>

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
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand sm:text-sm sm:leading-6"
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
                :autocomplete="selectedMode === 'login' ? 'current-password' : 'none'"
                name="password"
                type="password"
                required
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand sm:text-sm sm:leading-6"
              />
              <span class="text-red-500 text-sm" v-if="$page.props.errors?.password">{{
                  $page.props.errors?.password
                                                                                     }}</span>
            </div>
          </div>

          <div v-if="selectedMode === 'register'">
            <label
              for="password_confirmation"
              class="block text-sm font-medium leading-6 text-gray-900"
              v-t="'pages.auth.login.password_confirmation.label'"
            />
            <div class="mt-2">
              <input
                v-model="form.password_confirmation"
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                autocomplete="none"
                required
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand sm:text-sm sm:leading-6"
              />
            </div>
          </div>

          <div v-else class="flex items-center justify-between">
            <div class="flex items-center">
              <input
                v-model="form.rememberMe"
                id="remember-me"
                name="remember-me"
                type="checkbox"
                class="h-4 w-4 rounded border-gray-300 text-brand focus:ring-brand"
              />
              <label
                for="remember-me"
                class="ml-3 block text-sm leading-6 text-gray-900"
                v-t="'pages.auth.login.remember_me.label'"
              />
            </div>

            <div class="text-sm leading-6">
              <a
                class="font-semibold text-brand hover:text-brand-hover text"
                @click="forgotPassword"
              >
                {{ $t('pages.auth.login.forgot_password') }}
              </a>
            </div>
          </div>

          <div v-if="selectedMode === 'login'">
            <button
              type="submit"
              class="flex w-full justify-center rounded-md bg-brand px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-brand-hover focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand"
            >
              {{ $t('pages.auth.login.submit_button') }}
            </button>
          </div>

          <div v-else>
            <button
              class="flex w-full justify-center rounded-md bg-brand px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-brand-hover focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand"
              @click="register"
            >
              {{ $t('pages.auth.login.register_button') }}
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
import { useForm } from '@inertiajs/vue3';

export default defineComponent({
  layout: AuthLayout,

  data () {
    return {
      form: useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        rememberMe: false,
      }),
      tabs: {
        login: { name: this.$t('pages.auth.login.login_tab') },
        register: { name: this.$t('pages.auth.login.register_tab') },
      },
      selectedMode: 'login',
    };
  },

  methods: {
    changeMode (mode: string) {
      this.selectedMode = mode;
    },

    login () {
      this.form.transform(data => ({
        email: data.email,
        password: data.password,
        rememberMe: data.rememberMe ? 'on' : null,
      })).post(route('login'));
    },

    register () {
      console.log('Registering', this.form.data());
      this.form.transform(data => ({
        name: data.name,
        email: data.email,
        password: data.password,
        password_confirmation: data.password_confirmation,
      })).post(route('register'));
    },

    forgotPassword () {
      // TODO
      console.log('To be implemented');
    },
  },

  mounted () {
    console.log(this.$page.props);
  },
});
</script>
