import { QueryBuilderShowRequest } from '../../Base/QueryBuilderShowRequest';
import { ServerData } from '../../../Types/generated';

export class ServerShowRequest extends QueryBuilderShowRequest<ServerData> {
  protected getEndPoint() {
    return route('api.servers.show', { id: this.getId() }) as string;
  }
}
