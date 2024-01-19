<template>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import { FormType } from '../../../Enums/FormType';

export default defineComponent({
  components: {},

  options: {
    type: {
      type: Number as PropType<FormType>,
      required: true,
    },
  },

  data () {
    return {};
  },

  methods: {},

  computed: {},
});
</script>
