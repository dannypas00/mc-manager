import { defineStore } from 'pinia';
import User = App.Models.User;

export const useUserStore = defineStore('User', {
  state: () => ({
    user: {} as User,
  }),

  actions: {
    async setCurrentUser (id: number) {
      // TODO: Implement
    },
  },
});
