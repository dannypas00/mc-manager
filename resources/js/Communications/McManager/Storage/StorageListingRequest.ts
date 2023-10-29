import { Request } from '../../Base/Request';
import { Method } from 'axios';

export class StorageListingRequest extends Request<any> {
  private serverId = -1;
  private path = '/';

  protected getEndPoint () {
    return route('api.servers.storage.listing', { id: this.serverId, path: this.path });
  }

  protected getMethod (): Method {
    return 'GET';
  }

  public setServerId (serverId: number): this {
    this.serverId = serverId;
    return this;
  }

  public setPath (path: string): this {
    this.path = path;
    return this;
  }
}
