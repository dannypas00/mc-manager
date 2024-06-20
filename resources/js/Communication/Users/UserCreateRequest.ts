import { Request } from "../Base/Request";
import { UserData } from "../../Types/generated";
import { Method } from "axios";

export interface UserCreateData {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
  terms: boolean;
}

export class UserCreateRequest extends Request<UserData, UserCreateData> {
  protected getEndPoint(): string {
    return route("web.api.users.create") as string;
  }

  protected getMethod(): Method {
    return "POST";
  }

  public setData(data: UserCreateData): UserCreateRequest {
    this.data = data;

    return this;
  }
}
