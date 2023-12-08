import { Request } from '../../Base/Request';
import { Method } from 'axios';

export class StorageDeleteRequest extends Request<null, { paths: string[] }> {
  private id?: number;

  protected getEndPoint (): string {
    return route('api.servers.storage.delete', { id: this.id });
  }

  protected getMethod (): Method {
    return 'DELETE';
  }

  public setId (id: number): this {
    this.id = id;
    return this;
  }

  public setPaths (paths: string[]): this {
    this.data.paths = paths;
    return this;
  }
}
