import Server = App.Models.Server;
import { QueryBuilderShowRequest } from '../../Base/QueryBuilderShowRequest';

export class ServerShowRequest extends QueryBuilderShowRequest<Server> {
  protected getEndPoint () {
    return route('api.servers.show', { id: this.getId() });
  }
}
