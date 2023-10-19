import { defineStore } from 'pinia';
import User = App.Models.User;
import { UserShowRequest } from '../Communications/McManager/UserShowRequest';

export const useUserStore = defineStore('User', {
  state: () => ({
    user: {} as User,
    request: new UserShowRequest(),
  }),

  actions: {
    async setCurrentUser (id: number) {
      const response = await this.request.getResponse();
      this.user = response.data;
    },
  },
});
