import { Request } from "./Request";
import { QueryBuilderRequest } from './QueryBuilderRequest';

type QueryBuilderData = {
  filter?: Record<string, string | number>;
  page?: number;
  per_page?: number;
  include?: string[];
  sort?: string[];
  fields?: string[];
};

type QueryBuilderIndexResponse<T> = {
  data: T[];
  total: number;
};

export abstract class QueryBuilderIndexRequest<
  T,
  D = Record<string, never>,
> extends QueryBuilderRequest<QueryBuilderIndexResponse<T>, D & QueryBuilderData> {
  private page = 1;
  private perPage = 25;

  // Pagination:
  public setPage(page: number): this {
    this.page = page;
    this.data.page = this.page;
    return this;
  }

  public setPerPage(perPage: number): this {
    this.perPage = perPage;
    this.data.per_page = this.perPage;
    return this;
  }
}
