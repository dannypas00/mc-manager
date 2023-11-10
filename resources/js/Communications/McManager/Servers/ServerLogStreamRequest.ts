import { Request } from '../../Base/Request';
import { Method } from 'axios';

export class ServerLogStreamRequest extends Request<null> {
  private id?: number;

  protected getEndPoint () {
    return route('api.servers.logs', { id: this.id });
  }

  protected getMethod (): Method {
    return 'GET';
  }
}
