<template>
  <Portal to="layout-header">
    <div class="w-full px-4 sm:px-6 lg:px-8 flex justify-between">
      <div class="grow">
        <h1 class="text-lg font-semibold leading-6 text-gray-900">{{ header }}</h1>
      </div>

      <div class="grow-0">
        <span class="isolate inline-flex rounded-md shadow-sm">
          <template
            v-for="(button, index) in buttons"
          >
          <Link
            v-if="button.href"
            :href="button.href"
            as="button"
            class="relative inline-flex items-center bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-10 active:ring-2 active:ring-brand-light"
            :class="[{ 'rounded-l-md': index === 0, 'rounded-r-md': index === buttons.length - 1 }, button.additionalClasses]"
            :disabled="button.disabled ?? false"
            @click="button.onClick"
          >
            <FontAwesomeIcon v-if="button.icon" class="mr-2" v-bind="button.icon"/>
            {{ button.text }}
          </Link>
          <button
            v-else
            class="relative inline-flex items-center bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-10 active:ring-2 active:ring-brand-light"
            :class="[{ 'rounded-l-md': index === 0, 'rounded-r-md': index === buttons.length - 1 }, button.additionalClasses]"
            :disabled="button.disabled ?? false"
            @click="button.onClick"
          >
            <FontAwesomeIcon v-if="button.icon" class="mr-2" v-bind="button.icon"/>
            {{ button.text }}
            </button>
          </template>
        </span>
      </div>
    </div>
  </Portal>
</template>

<script setup lang="ts">
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { PageHeaderButton } from './PageHeaderButton';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  /**
   * Header used in navbar
   */
  header: {
    type: String,
    required: true,
  },

  /**
   * Title of the page, used in document / tab title
   */
  title: {
    type: String,
    required: false,
    default: null,
  },

  buttons: {
    type: Array<PageHeaderButton>,
    required: false,
    default: [],
  },
});

document.title = (props.title ?? props.header) + ' - ' + import.meta.env.VITE_APP_NAME;
</script>
