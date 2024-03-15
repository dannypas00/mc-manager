import { UserData } from '../../Types/generated';
import { QueryBuilderRequest } from '../Base/QueryBuilderRequest';
import axios, { Method } from 'axios';

export class UserAllRequest extends QueryBuilderRequest<UserData[]> {
  protected getEndPoint (): string {
    return route('api.users.all');
  }

  protected getMethod (): Method {
    return 'GET';
  }

}
