import { defineStore } from 'pinia';
import _ from 'lodash';
import { AxiosResponse } from 'axios';
import { useUserStore } from '../UserStore';
import { ServerShowRequest } from '../../Communications/McManager/Servers/ServerShowRequest';
import Server = App.Models.Server;
import { ServerData } from '../../Types/generated';

export const useServerEditStore = defineStore('ServerEdit', {
  state: () => ({
    model: {} as ServerData,
    original: {} as FormData,
    showRequest: new ServerShowRequest(),

    icon: null as File | null,
  }),

  getters: {
    isChanged(state): boolean {
      return !_.isEqual(this.dereferenced, state.original);
    },

    dereferenced(state): FormData {
      return JSON.parse(JSON.stringify(this.requestData));
    },

    requestData(state): FormData {
      const filtered = _.pick(state.model, ['type', 'name']);

      return {
        ...filtered,
      } as unknown as FormData;
    },
  },

  actions: {
    retrieve(id: number) {
      this.showRequest.setId(id).getResponse().then(this.setFromRequest);
    },

    setFromRequest(response: AxiosResponse<ServerData>) {
      this.setFromModel(response.data);
    },

    setFromModel(model: ServerData) {
      this.model = model;
      this.original = this.dereferenced;
    },

    setEmpty() {
      this.model = {
        enable_ftp: true,
        enable_ssh: true,
        users: [useUserStore().user],
        // Casting to unknown first because not all required fields are available yet
      } as unknown as ServerData;
      this.original = this.dereferenced;
    },
  },
});
