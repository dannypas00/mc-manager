import { computed, ModelRef } from "vue";

export function usePassthroughModel<T>(
  emit: unknown,
  modelValue: ModelRef<T>,
  name: string = "model-value",
) {
  return computed({
    get: () => modelValue.value,
    // eslint-disable-next-line @typescript-eslint/ban-ts-comment
    // @ts-expect-error
    set: (value: T) => emit(`update:${name}`, value),
  });
}
