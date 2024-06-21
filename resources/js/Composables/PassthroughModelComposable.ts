import { computed, ModelRef } from "vue";

export function usePassthroughModel<T>(emit: unknown, modelValue: ModelRef<T>, name: string = "model-value") {
  return computed({
    get: () => modelValue.value,
    // @ts-ignore
    set: (value: T) => emit(`update:${name}`, value),
  });
}
