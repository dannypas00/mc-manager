import { defineStore } from 'pinia';
import { UserData } from '../Types/generated';
import { usePage } from '@inertiajs/vue3';
import { onMounted, Ref, ref } from 'vue';
import _ from 'lodash';

export const useAuthenticatedUserStore = defineStore(
  'authenticated-user',
  () => {
    const user: Ref<UserData | null> = ref(
      usePage().props.user as UserData | null
    );

    onMounted(() => {
      if (user.value) {
        window.Echo.channel(`users.${user.value.id}`).listen(
          '.UserUpdated',
          (event: { model: UserData }) => {
            console.log('Received user update from reverb!', event);
            user.value = _.assign(user.value, event.model);
          }
        );
      }
    });

    return { user };
  }
);
