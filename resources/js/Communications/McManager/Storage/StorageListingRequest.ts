import { Request } from '../../Base/Request';
import { Method } from 'axios';
import { FileEntry } from '../../../Types/FileEntry';

export class StorageListingRequest extends Request<{ directories?: FileEntry[]; file?: string }, { path: string }> {
  private serverId = -1;

  protected getEndPoint() {
    return route('api.servers.storage.listing', { id: this.serverId });
  }

  protected getMethod(): Method {
    return 'GET';
  }

  public setServerId(serverId: number): this {
    this.serverId = serverId;
    return this;
  }

  public setPath(path: string): this {
    this.data.path = path;
    return this;
  }
}
