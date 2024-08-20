import Server = App.Models.Server;
import { Request } from '../../Base/Request';
import { Method } from 'axios';

export class ServerUpdateRequest extends Request<Server> {
  private id?: number;

  protected getEndPoint (): string {
    return route('api.servers.update', { id: this.id }) as string;
  }

  protected getMethod (): Method {
    return 'PUT';
  }

  public setId (id: number): this {
    this.id = id;
    return this;
  }

  public setData (data: FormData): this {
    this.data = data;
    return this;
  }
}
