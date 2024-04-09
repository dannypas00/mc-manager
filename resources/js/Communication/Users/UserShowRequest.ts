import { QueryBuilderShowRequest } from '../Base/QueryBuilderShowRequest';
import { route } from 'ziggy-js';
import { UserData } from '../../Types/generated';

export class UserShowRequest extends QueryBuilderShowRequest<UserData>{
  protected getEndPoint (): string {
    return route('web.api.users.show', {id: this.getId()});
  }
}
