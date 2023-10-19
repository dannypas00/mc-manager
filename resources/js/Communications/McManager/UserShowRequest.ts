import { Request } from '../Base/Request';
import { Method } from 'axios';
import User = App.Models.User;

export class UserShowRequest extends Request<User> {
  private id?: number;

  protected getEndPoint () {
    return route('api.users.show', { id: this.id });
  }

  protected getMethod (): Method {
    return 'GET';
  }

  public setId (id: number): this {
    this.id = id;
    return this;
  }
}
