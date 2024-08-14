import { QueryBuilderShowRequest } from '../../Base/QueryBuilderShowRequest';
import User = App.Models.User;
import { UserData } from '../../../Types/generated';

export class UserShowRequest extends QueryBuilderShowRequest<UserData> {
  protected getEndPoint(): string {
    return route('api.users.show', { id: this.getId() }) as string;
  }
}
