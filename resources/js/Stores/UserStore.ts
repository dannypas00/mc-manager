import { defineStore } from 'pinia';
import { UserShowRequest } from '../Communications/McManager/Users/UserShowRequest';
import { UserData } from '../Types/generated';

export const useUserStore = defineStore('User', {
  state: () => ({
    user: {} as UserData,
    request: new UserShowRequest(),
  }),

  actions: {
    async setCurrentUser(id: number) {
      const response = await this.request
        .addInclude('servers')
        .setId(id)
        .getResponse();

      this.user = response.data;
    },
  },
});
