import { Request } from '../Base/Request';
import { UserData } from '../../Types/generated';
import { Method } from 'axios';

export class UserUpdateRequest extends Request<UserData> {
  private identifier: number;

  protected getMethod(): Method {
    return 'PUT';
  }

  protected getEndPoint(): string {
    return route('web.api.users.update', this.identifier);
  }

  public setIdentifier(identifier: number): this {
    this.identifier = identifier;
    return this;
  }

  public setData(data: Partial<UserData>): this {
    this.data = data;
    return this;
  }
}
