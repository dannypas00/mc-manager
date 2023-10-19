import { Request } from './Request';

type QueryBuilderData = {
  filter?: Record<string, string | number>;
  page?: number;
  per_page?: number;
  include?: string[];
  sort?: string[];
}

export abstract class QueryBuilderRequest<T, D = Record<string, any>> extends Request<T, D & QueryBuilderData> {
  private page: number = 1;
  private perPage: number = 25;
  private filter: Record<string, string | number> = {};
  private sort: string[] = [];
  private include: string[] = [];
  private fields: string[] = ['*'];
  private appends: string[] = [];

  // Pagination:
  public setPage (page: number): this {
    this.page = page;
    this.data.page = this.page;
    return this;
  }

  public setPerPage (perPage: number): this {
    this.perPage = perPage;
    this.data.per_page = this.perPage;
    return this;
  }

  // Includes
  public setInclude (include: string[]): this {
    this.include = include;
    this.data.include = this.include;
    return this;
  }

  public addInclude (include: string): this {
    this.include.push(include);
    this.data.include = this.include;
    return this;
  }

  public removeInclude (include: string): this {
    let index = this.include.findIndex(entry => entry === include || entry === `-${include}`);
    if (index > -1) {
      this.include.splice(index, 1);
    }
    this.data.include = this.include;
    return this;
  }

  public clearInclude (): this {
    this.include = [];
    this.data.include = this.include;
    return this;
  }

  // Sorting
  public setSort (sorting: string[]): this {
    this.sort = sorting;
    this.data.sort = this.sort;
    return this;
  }

  public addSort (sorting: string): this {
    this.sort.push(sorting);
    this.data.sort = this.sort;
    return this;
  }

  public removeSort (sorting: string): this {
    let index = this.sort.findIndex(entry => entry === sorting || entry === `-${sorting}`);
    if (index > -1) {
      this.sort.splice(index, 1);
    }
    this.data.sort = this.sort;
    return this;
  }

  public clearSort (): this {
    this.sort = [];
    this.data.sort = this.sort;
    return this;
  }

  // Filtering
  public setFilters (filters: Record<string, string | number>): this {
    this.filter = filters;
    this.data.filter = this.filter;
    return this;
  }

  public addFilter (filter: string, value: string | number): this {
    this.filter[filter] = value;
    this.data.filter = this.filter;
    return this;
  }

  public removeFilter (filter: string): this {
    delete this.filter[filter];
    this.data.filter = this.filter;
    return this;
  }

  public clearFilters (): this {
    this.filter = {};
    this.data.filter = this.filter;
    return this;
  }

  // TODO: Fields and appends
}
