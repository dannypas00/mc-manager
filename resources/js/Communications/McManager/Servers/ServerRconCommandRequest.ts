import { Request } from '../../Base/Request';
import { Method } from 'axios';

export class ServerRconCommandRequest extends Request<string> {
  private id?: number;

  protected getEndPoint (): string {
    return route('api.servers.command', { id: this.id });
  }

  protected getMethod (): Method {
    return 'POST';
  }

  public setId (id: number): ServerRconCommandRequest {
    this.id = id;
    return this;
  }

  public setCommand (command: string): ServerRconCommandRequest {
    this.data.command = command;
    return this;
  }
}
