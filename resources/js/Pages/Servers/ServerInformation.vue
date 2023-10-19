<template>
  {{ store.model.name ?? 'test' }}
</template>

<script setup lang="ts">
import { useServerShowStore } from '../../Stores/Servers/ServerShowStore';
import { reactive } from 'vue';

const props = defineProps({
  id: {
    type: Number,
    required: true,
  },
});

const store = reactive(useServerShowStore());

await useServerShowStore().getServer(props.id);
</script>
