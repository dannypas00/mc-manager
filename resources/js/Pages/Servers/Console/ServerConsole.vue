<template>
  <!-- Terminal -->
  <div class="w-full h-[50em] bg-slate-900 text-white mx-auto">
    <!-- Text render -->
    <code class="m-1">
      test
    </code>

    <!-- Inputbox -->
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import ServerShowTemplate from '../ServerShowTemplate.vue';

export default defineComponent({
  components: {},

  layout: ServerShowTemplate,

  data () {
    return {};
  },
});
</script>
