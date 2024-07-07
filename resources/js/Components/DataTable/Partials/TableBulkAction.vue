<template>
  <button
    type="button"
    :class="[
      'inline-flex items-center',
      'rounded bg-white px-2 py-1 text-sm font-semibold text-gray-900',
      'shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50',
      'disabled:cursor-not-allowed disabled:opacity-30 disabled:hover:bg-white',
      action.classes,
    ]"
    @click="onActionClick"
  >
    <FontAwesomeIcon v-if="action.icon" class="mr-1" v-bind="action.icon" />
    {{ action.title }}
  </button>

  <ConfirmationDialog
    v-model:open="confirmationOpen"
    :title="$t('components.datatable.bulk_actions.confirm_title')"
    :message="confirmationText"
    :positive-button-text="
      $t('components.datatable.bulk_actions.confirm_positive_button')
    "
    :negative-button-text="
      $t('components.datatable.bulk_actions.confirm_negative_button')
    "
    @positive="onActionConfirm"
  />
</template>

<script setup lang="ts" generic="T extends Record<string, any>">
import { computed, PropType, ref } from 'vue';
import { BulkOption } from '../DataTableTypes';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import ConfirmationDialog from '../../Dialogs/ConfirmationDialog.vue';
import _ from 'lodash';
import I18n from '../../../i18n';

const selected = defineModel('selected', {
  type: Array<T>,
  required: true,
});

const props = defineProps({
  action: {
    type: Object as PropType<BulkOption<T>>,
    required: true,
  },
});

const confirmationOpen = ref(false);

function onActionClick() {
  if (props.action?.confirmation) {
    confirmationOpen.value = true;
    return;
  }
  onActionConfirm();
}

function onActionConfirm() {
  if (props.action?.unselectAfter) {
    selected.value = [];
  }

  if (props.action?.onClick) {
    props.action?.onClick(selected.value);
  }
}

const confirmationText = computed(() => {
  if (_.isFunction(props.action?.confirmationText)) {
    return props.action.confirmationText(selected.value);
  }
  if (_.isString(props.action?.confirmationText)) {
    return props.action?.confirmationText;
  }
  return I18n.global.t(
    'components.datatable.bulk_actions.default_confirm_text',
    { count: selected.value.length, action: props.action.title }
  );
});
</script>
