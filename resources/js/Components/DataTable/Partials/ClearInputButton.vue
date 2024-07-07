<template>
  <FontAwesomeIcon
    :icon="{ iconName: 'x', prefix: 'fas' }"
    class="cursor-pointer p-2 text-red-400"
    size="sm"
    :aria-title="$t('components.datatable.clear_title')"
  />
</template>

<script setup lang="ts">
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
</script>
