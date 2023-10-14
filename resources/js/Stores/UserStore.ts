import { defineStore } from 'pinia';
import User = App.Models.User;
import { UserResource } from '../Communications/resources/UserResource';

export const useUserStore = defineStore('User', {
  state: () => ({
    user: {} as User,
  }),

  actions: {
    async setCurrentUser (id: number) {
      this.user = await UserResource.$find(id);
    },
  },
});
