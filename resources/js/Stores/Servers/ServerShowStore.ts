import { defineStore } from 'pinia';
import { ServerShowRequest } from '../../Communications/McManager/Servers/ServerShowRequest';
import { ServerEulaAcceptedRequest } from '../../Communications/McManager/Servers/ServerEulaAcceptedRequest';
import { ServerData } from '../../Types/generated';

export const useServerShowStore = defineStore('ServerShow', {
  state: () => ({
    request: new ServerShowRequest(),
    model: {} as ServerData,
    eulaAccepted: true,
    eulaAcceptedRequest: new ServerEulaAcceptedRequest(),
  }),

  actions: {
    async getServer(id: number) {
      // Requesting EULA first because we don't await it
      this.updateEulaAccepted(id);

      const response = await this.request.setId(id).getResponse();
      await new Promise(resolve => setTimeout(resolve, 1000));
      this.model = response.data;

      return response;
    },

    updateEulaAccepted(id: number) {
      this.eulaAcceptedRequest
        .setId(id)
        .getResponse()
        .then(response => {
          this.eulaAccepted = response.data;
        });
    },
  },
});
