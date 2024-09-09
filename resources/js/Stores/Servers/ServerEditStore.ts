import { defineStore } from 'pinia';
import _ from 'lodash';
import { AxiosResponse } from 'axios';
import { useUserStore } from '../UserStore';
import { ServerShowRequest } from '../../Communications/McManager/Servers/ServerShowRequest';
import { ServerUpdateRequest } from '../../Communications/McManager/Servers/ServerUpdateRequest';
import { ServerData } from '../../Types/generated';

export const useServerEditStore = defineStore('ServerEdit', {
  state: () => ({
    model: {} as ServerData,
    original: {} as unknown,
    showRequest: new ServerShowRequest(),
    updateRequest: new ServerUpdateRequest(),

    icon: null as File | null,
    customizeInstallProcess: false,
    dockerImage: '',
  }),

  getters: {
    isChanged (state): boolean {
      return !_.isEqual(this.dereferenced, state.original);
    },

    dereferenced (): Record<string, any> {
      return JSON.parse(JSON.stringify(this.requestData));
    },

    requestData (state): Record<string, any> {
      const filtered = _.pick(
        state.model,
        [
          'enabled',
          'name',
          'description',
          'public_ip',
          'port',
          'rcon_port',
          'local_ip',
          'enable_ssh',
          'ssh_port',
          'ssh_username',
          'ssh_key',
          'enable_ftp',
          'is_sftp',
          'ftp_port',
          'ftp_username',
          'ftp_password',
          'version',
        ],
      );

      console.log(filtered, state.model);

      console.log(state.icon);
      return {
        ...filtered,
        icon_file: state.icon,
        is_custom: state.customizeInstallProcess,
        custom_docker_image: state.dockerImage,
        // custom_jar: state.customJar,
      };
    },
  },

  actions: {
    retrieve (id: number) {
      this.showRequest.setId(id).getResponse().then(this.setFromRequest);
    },

    setFromRequest (response: AxiosResponse<ServerData>) {
      this.setFromModel(response.data);
    },

    setFromModel (model: ServerData) {
      this.model = model;
      this.original = this.dereferenced;
    },

    setEmpty () {
      this.model = {
        enabled: false,
        enable_ftp: true,
        enable_ssh: true,
        users: [useUserStore().user],
      } as ServerData;
      this.original = this.dereferenced;
    },

    async update () {
      return this.updateRequest
        .setId(this.model.id)
        .setData(this.requestData)
        .getResponse();
    },
  },
});
