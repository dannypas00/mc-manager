import { Request } from '../../Base/Request';
import { Method } from 'axios';

export class ServerEulaAcceptedRequest extends Request<boolean> {
  private id?: number;

  protected getEndPoint (): string {
    return route('api.servers.eula-accepted', { id: this.id });
  }

  protected getMethod (): Method {
    return 'GET';
  }

  public setId (id: number): this {
    this.id = id;
    return this;
  }
}
