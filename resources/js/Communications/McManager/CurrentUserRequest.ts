import { Request } from '../Base/Request';
import User = App.Models.User;
import { Method } from 'axios';

export class CurrentUserRequest extends Request<User>{
  protected getEndPoint () {
    return route('api.auth.user.current');
  }

  protected getMethod (): Method {
    return "GET";
  }
}
