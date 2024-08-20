import { Method } from 'axios';
import { QueryBuilderRequest } from './QueryBuilderRequest';

export abstract class QueryBuilderShowRequest<
  T,
> extends QueryBuilderRequest<T> {
  private id?: number;

  protected getMethod(): Method {
    return 'GET';
  }

  public setId(id: number): this {
    this.id = id;
    return this;
  }

  protected getId(): number {
    return this.id ?? -1;
  }
}
