<template>
  <RadioGroup v-model="selectedOption">
    <RadioGroupLabel class="text-base font-semibold leading-6 text-gray-900">
      {{ $t('pages.servers.create.type_selector.title') }}
    </RadioGroupLabel>

    <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-3 sm:gap-x-4">
      <RadioGroupOption
        v-for="option in options"
        :key="option"
        v-slot="{ active, checked }"
        as="template"
        :value="option"
      >
        <div
          :class="[
            active ? 'border-emerald-400 ring-2 ring-emerald-500' : 'border-gray-300',
            'relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none'
          ]"
        >
          <span class="flex flex-1">
            <span class="flex flex-col">
              <RadioGroupLabel as="span" class="block text-sm font-medium text-gray-900">
                {{ option.name }}
              </RadioGroupLabel>
              <RadioGroupDescription as="p" class="mt-1 flex items-center text-sm text-gray-500 whitespace-pre-line">
                {{ option.description }}
              </RadioGroupDescription>
            </span>
          </span>

          <CheckCircleIcon
            :class="[
              !checked ? 'invisible' : '',
              'h-5 w-5 text-emerald-600'
            ]"
            aria-hidden="true"
          />

          <span
            :class="[
              active ? 'border' : 'border-2',
              checked ? 'border-emerald-600' : 'border-transparent',
              'pointer-events-none absolute -inset-px rounded-lg'
            ]"
            aria-hidden="true"
          />
        </div>
      </RadioGroupOption>
    </div>
  </RadioGroup>

  <PositiveButton
    v-if="selectedOption"
    :title="$t(
      'pages.servers.create.type_selector.next_title',
      { type: selectedOption?.name ?? '-' }
    )"
    class="h-8 w-10 justify-center float-right mt-2 mb-6 sm:mb-0"
    @click="save"
  >
    <FontAwesomeIcon icon="arrow-right"/>
  </PositiveButton>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { RadioGroup, RadioGroupDescription, RadioGroupLabel, RadioGroupOption } from '@headlessui/vue';
import { CheckCircleIcon } from '@heroicons/vue/20/solid';
import PositiveButton from '../../../Components/Buttons/PositiveButton.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import ServerType = App.Models.ServerType;

interface ServerOption {
  value: ServerType,
  name: string,
  description: string,
}

export default defineComponent({
  emits: ['save'],

  components: {
    FontAwesomeIcon,
    PositiveButton,
    RadioGroup,
    RadioGroupDescription,
    RadioGroupLabel,
    RadioGroupOption,
    CheckCircleIcon,
  },

  data () {
    return {
      options: [
        {
          value: 1,
          name: this.$t('pages.servers.create.type_selector.names.1'),
          description: this.$t('pages.servers.create.type_selector.description.1'),
        },
        {
          value: 2,
          name: this.$t('pages.servers.create.type_selector.names.2'),
          description: this.$t('pages.servers.create.type_selector.description.2'),
        },
        {
          value: 3,
          name: this.$t('pages.servers.create.type_selector.names.3'),
          description: this.$t('pages.servers.create.type_selector.description.3'),
        },
      ] as ServerOption[],

      selectedOption: null as ServerOption | null,
    };
  },

  methods: {
    save () {
      this.$emit('save', this.selectedOption?.value);
    },
  },
});
</script>
