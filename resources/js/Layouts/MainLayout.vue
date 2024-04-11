<template>
  <slot />
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
  components: {},

  inheritAttrs: true,

  data() {
    return {};
  },
});
</script>
