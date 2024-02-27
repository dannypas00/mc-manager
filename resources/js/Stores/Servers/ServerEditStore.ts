import { defineStore } from 'pinia';
import _ from 'lodash';
import { AxiosResponse } from 'axios';
import { useUserStore } from '../UserStore';
import { ServerShowRequest } from '../../Communications/McManager/Servers/ServerShowRequest';
import Server = App.Models.Server;

export const useServerEditStore = defineStore('ServerEdit', {
  state: () => ({
    model: {} as Server,
    original: {} as FormData,
    showRequest: new ServerShowRequest(),

    icon: null as File | null,
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
        port: 25565,
        rcon_port: 25575,
        ssh_port: 22,
        ftp_port: 21,
        ftp_username: 'mc-manager',
        enable_ftp: true,
        enable_ssh: true,
        users: [
          useUserStore().user,
        ],
      } as Server;
      this.original = this.dereferenced;
    },
  },
});
