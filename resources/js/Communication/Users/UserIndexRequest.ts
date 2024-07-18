import { QueryBuilderIndexRequest } from '../Base/QueryBuilderIndexRequest';
import { UserData } from '../../Types/generated';
import { Method } from 'axios';

export class UserIndexRequest extends QueryBuilderIndexRequest<UserData> {
  protected getEndPoint(): string {
    return route('web.api.users.index') as string;
  }

  protected getMethod(): Method {
    return 'GET';
  }
}
