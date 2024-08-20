import { defineStore } from 'pinia';
import _ from 'lodash';
import { AxiosResponse } from 'axios';
import { useUserStore } from '../UserStore';
import { ServerShowRequest } from '../../Communications/McManager/Servers/ServerShowRequest';
import Server = App.Models.Server;
import { ServerUpdateRequest } from '../../Communications/McManager/Servers/ServerUpdateRequest';
import { ServerData } from '../../Types/generated';

export const useServerEditStore = defineStore('ServerEdit', {
  state: () => ({
    model: {} as ServerData,
    original: {} as FormData,
    showRequest: new ServerShowRequest(),
    updateRequest: new ServerUpdateRequest(),

    icon: null as File | null,
    customizeInstallProcess: false,
    serverProperties: '',
    dockerImage: '',
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
      this.serverProperties =
        'enable-jmx-monitoring=false\n' +
        'level-seed=\n' +
        'gamemode=survival\n' +
        'enable-command-block=false\n' +
        'generator-settings={}\n' +
        'enforce-secure-profile=true\n' +
        'level-name=world\n' +
        'motd=A Minecraft Server\n' +
        'pvp=true\n' +
        'generate-structures=true\n' +
        'max-chained-neighbor-updates=1000000\n' +
        'difficulty=easy\n' +
        'network-compression-threshold=256\n' +
        'max-tick-time=60000\n' +
        'require-resource-pack=false\n' +
        'use-native-transport=true\n' +
        'max-players=20\n' +
        'online-mode=true\n' +
        'enable-status=true\n' +
        'allow-flight=false\n' +
        'initial-disabled-packs=\n' +
        'broadcast-rcon-to-ops=true\n' +
        'view-distance=10\n' +
        'resource-pack-prompt=\n' +
        'allow-nether=true\n' +
        'enable-rcon=false\n' +
        'sync-chunk-writes=true\n' +
        'op-permission-level=4\n' +
        'prevent-proxy-connections=false\n' +
        'hide-online-players=false\n' +
        'resource-pack=\n' +
        'entity-broadcast-range-percentage=100\n' +
        'simulation-distance=10\n' +
        'player-idle-timeout=0\n' +
        'force-gamemode=false\n' +
        'rate-limit=0\n' +
        'hardcore=false\n' +
        'white-list=false\n' +
        'broadcast-console-to-ops=true\n' +
        'spawn-npcs=true\n' +
        'spawn-animals=true\n' +
        'log-ips=true\n' +
        'function-permission-level=2\n' +
        'initial-enabled-packs=vanilla\n' +
        'level-type=minecraft\\:normal\n' +
        'text-filtering-config=\n' +
        'spawn-monsters=true\n' +
        'enforce-whitelist=false\n' +
        'spawn-protection=16\n' +
        'resource-pack-sha1=\n' +
        'max-world-size=29999984';
      this.original = this.dereferenced;
    },

    async update () {
      return this.updateRequest
        .setId(this.model.id)
        .setData(this.requestData)
        .getResponse();
    }
  },
});
