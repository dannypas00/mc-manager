import { defineStore } from 'pinia';
import _ from 'lodash';
import Server = App.Models.Server;
import { AxiosResponse } from 'axios';
import { useUserStore } from '../UserStore';
import { ServerShowRequest } from '../../Communications/McManager/Servers/ServerShowRequest';

export const useServerEditStore = defineStore('ServerEdit', {
  state: () => ({
    model: {} as Server,
    original: {} as FormData,
    showRequest: new ServerShowRequest(),
  }),

  getters: {
    isChanged (state): boolean {
      return !_.isEqual(
        state.dereferenced,
        state.original,
      );
    },

    dereferenced (state): FormData {
      return JSON.parse(JSON.stringify(state.requestData));
    },

    requestData (state): FormData {
      const filtered = _.pick(state.model, [
        // Array of allowed keys
      ]);

      return {
        ...filtered,
      } as unknown as FormData;
    },
  },

  actions: {
    setFromRequest (response: AxiosResponse<Server>) {
      this.setFromModel(response.data);
    },

    setFromModel (model: Server) {
      this.model = model;
      this.original = this.dereferenced;
    },

    setEmpty () {
      this.model = {
        users: [
          useUserStore().user,
        ],
      } as Server;
      this.original = this.dereferenced;
    },
  },
});
