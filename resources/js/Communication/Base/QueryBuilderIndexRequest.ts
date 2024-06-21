import {
  QueryBuilderIndexData,
  QueryBuilderRequest,
} from "./QueryBuilderRequest";

type QueryBuilderIndexResponse<T extends Record<string, unknown>> = {
  data: T[];
  total: number;
};

export abstract class QueryBuilderIndexRequest<
  T extends Record<string, unknown>,
> extends QueryBuilderRequest<
  QueryBuilderIndexResponse<T>,
  QueryBuilderIndexData<T>
> {
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
