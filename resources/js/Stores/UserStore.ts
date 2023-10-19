import { defineStore } from 'pinia';
import { UserShowRequest } from '../Communications/McManager/Users/UserShowRequest';
import User = App.Models.User;

export const useUserStore = defineStore('User', {
  state: () => ({
    user: {} as User,
    request: new UserShowRequest(),
  }),

  actions: {
    async setCurrentUser (id: number) {
      const response = await this.request
        .addInclude('servers')
        .setId(id)
        .getResponse();

      this.user = response.data;
    },
  },
});
