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
        'type',
        'name',
      ]);

      return {
        ...filtered,
      } as unknown as FormData;
    },
  },

  actions: {
    retrieve (id: number) {
      this.showRequest
        .setId(id)
        .getResponse()
        .then(this.setFromModel)
    },

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
