import { QueryBuilderShowRequest } from '../../Base/QueryBuilderShowRequest';
import User = App.Models.User;

export class UserShowRequest extends QueryBuilderShowRequest<User> {
  protected getEndPoint () {
    return route('api.users.show', { id: this.getId() });
  }
}
