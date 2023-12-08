import { Request } from '../../Base/Request';
import { Method } from 'axios';

export class StorageWriteRequest extends Request<null, { content: string }> {
  private id?: number;
  private path?: string;

  protected getEndPoint (): string {
    return route('api.servers.storage.write', { id: this.id, path: this.path });
  }

  protected getMethod (): Method {
    return 'POST';
  }

  public setContent (content: string): this {
    this.data.content = content;
    return this;
  }

  public setId (id: number): this {
    this.id = id;
    return this;
  }

  public setPath (path: string): this {
    this.path = path;
    return this;
  }
}
