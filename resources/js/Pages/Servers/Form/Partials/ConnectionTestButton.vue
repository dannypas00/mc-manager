<template>
  <div class="block">
    <NeutralButton
      class="mr-4 inline-block"
      :text="text"
      @click="connectionTest"
    />
    <span v-if="loading" class="inline-block text-blue-600">
      <!-- Loading -->
      <FontAwesomeIcon icon="fa-solid fa-circle-notch" spin />
    </span>
    <span
      v-else-if="connectionStatus === true"
      class="inline-block text-green-400"
    >
      <!-- Successful connection -->
      <FontAwesomeIcon icon="check" />
    </span>
    <span
      v-else-if="connectionStatus === false"
      class="inline-block text-red-500"
    >
      <!-- Failed connection -->
      <FontAwesomeIcon icon="x" />
    </span>

    <span v-else class="inline-block text-yellow-500">
      <!-- Unknown connection -->
      <FontAwesomeIcon icon="question-circle" />
    </span>
  </div>
</template>

<script setup lang="ts">
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import NeutralButton from '../../../../Components/Buttons/NeutralButton.vue';
import { PropType, Ref, ref } from 'vue';

const props = defineProps({
  test: {
    type: Function as PropType<() => Promise<boolean>>,
    required: true,
  },
  text: {
    type: String,
    required: true,
  },
});

const connectionStatus: Ref<boolean | undefined> = ref(undefined);
const loading = ref(false);

async function connectionTest() {
  loading.value = true;
  connectionStatus.value = await props.test();
  loading.value = false;
}
</script>
