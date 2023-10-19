import { defineStore } from 'pinia';
import { ServerShowRequest } from '../../Communications/McManager/Servers/ServerShowRequest';
import Server = App.Models.Server;

export const useServerShowStore = defineStore('ServerShow', {
  state: () => ({
    request: new ServerShowRequest(),
    model: {} as Server,
  }),

  actions: {
    async getServer (id: number) {
      const response =
        await this.request
          .setId(id)
          .getResponse();
      await new Promise(resolve => setTimeout(resolve, 1000));
      this.model = response.data;

      return response;
    },
  },
});
