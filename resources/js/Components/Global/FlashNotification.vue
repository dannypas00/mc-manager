<template>
  <div class="flex items-center gap-x-6 px-6 py-2.5 sm:px-3.5 sm:before:flex-1 rounded-xl" :class="[ backgroundColor ]">
    <p class="text-sm leading-6 text-center font-semibold">
      <Link
        :class="textColor"
        :href="isString(onClickAction) ? onClickAction : '#'"
        class="font-semibold"
        @click="isFunction(onClickAction) ? onClickAction : () => {}"
      >
        {{ text }}
      </Link>

      <Link
        v-if="buttonText"
        :href="isString(onClickAction) ? onClickAction : '#'"
        :class="[ buttonBackgroundColor, buttonTextColor ]"
        class="flex-none rounded-full mx-3 px-3.5 py-1 text-sm shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900"
        @click="isFunction(onClickAction) ? onClickAction : () => {}"
      >
        {{ buttonText }} <span aria-hidden="true">&rarr;</span>
      </Link>
    </p>

    <div class="flex flex-1 justify-end">
      <button
        v-if="dismissButton"
        type="button"
        class="-m-3 p-3 focus-visible:outline-offset-[-4px]"
      >
        <span v-t="'components.flash.dismiss'" class="sr-only"/>
        <XMarkIcon
          :class="[ buttonTextColor ]"
          class="h-5 w-5"
          aria-hidden="true"
        />
      </button>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import { XMarkIcon } from '@heroicons/vue/24/outline';
import { isFunction, isString } from 'lodash';
import { Link } from '@inertiajs/vue3';

export default defineComponent({
  components: {
    XMarkIcon,
    Link,
  },

  props: {
    text: {
      type: String,
      required: true,
    },

    buttonText: {
      type: String,
      required: false,
      default: undefined,
    },

    dismissButton: {
      type: Boolean,
      required: false,
      default: true,
    },

    backgroundColor: {
      type: String,
      required: false,
      default: 'bg-amber-400',
    },

    textColor: {
      type: String,
      required: false,
      default: 'text-white',
    },

    buttonBackgroundColor: {
      type: String,
      required: false,
      default: 'bg-amber-200 hover:bg-amber-600',
    },

    buttonTextColor: {
      type: String,
      required: false,
      default: 'text-gray-900 hover:text-white',
    },

    onClickAction: {
      type: [Function, String] as PropType<typeof Function | string>,
      required: false,
      default: undefined,
    },
  },

  methods: {
    isFunction,
    isString,
  },
});
</script>
