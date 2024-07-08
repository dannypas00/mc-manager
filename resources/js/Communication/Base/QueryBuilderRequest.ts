import { Request } from './Request';
import _, { uniq } from 'lodash';
import { SortDirection } from '../../Utilities/SortDirection';

type QueryBuilderData = {
  filter?: Record<string, unknown>;
  include?: string[];
  sort?: string[];
  fields?: string[];
};

export type QueryBuilderIndexData<T extends Record<string, unknown>> =
  QueryBuilderData & {
    data: T[];
    links: Array<unknown>;
    meta: {
      current_page: number;
      last_page: number;
      from: number;
      per_page: number;
      to: number;
      total: number;
    };
  };

type QueryBuilderResponseData<T extends Record<string, unknown>> =
  | T
  | QueryBuilderIndexData<T>;

export abstract class QueryBuilderRequest<
  T extends Record<string, unknown>,
  D extends QueryBuilderData | QueryBuilderIndexData<T> = QueryBuilderData,
> extends Request<T & QueryBuilderResponseData<T>, D> {
  private filter: Record<string, unknown> = {};
  private sort: string[] = [];
  private include: string[] = [];
  private fields: string[] = ['*'];

  // Includes
  public setInclude(include: string[]): this {
    this.include = include;
    this.data.include = this.include;
    return this;
  }

  public addInclude(include: string): this {
    this.include.push(include);
    this.data.include = this.include;
    return this;
  }

  public removeInclude(include: string): this {
    const index = this.include.findIndex(
      entry => entry === include || entry === `-${include}`
    );
    if (index > -1) {
      this.include.splice(index, 1);
    }
    this.data.include = this.include;
    return this;
  }

  public clearInclude(): this {
    this.include = [];
    this.data.include = this.include;
    return this;
  }

  // Sorting
  public setSort(sorting: Record<string, SortDirection>): this {
    // If ascending, set key, if descending set -key
    this.sort = _(sorting).map((value: SortDirection, key: string) => {
      if (value === SortDirection.None) {
        return null;
      }
      return value === SortDirection.Desc ? `-${key}` : key;
    }).filter().value();
    this.data.sort = this.sort;
    return this;
  }

  public addSort(sorting: string): this {
    this.sort.push(sorting);
    this.data.sort = this.sort;
    return this;
  }

  public removeSort(sorting: string): this {
    const index = this.sort.findIndex(
      entry => entry === sorting || entry === `-${sorting}`
    );
    if (index > -1) {
      this.sort.splice(index, 1);
    }
    this.data.sort = this.sort;
    return this;
  }

  public clearSort(): this {
    this.sort = [];
    this.data.sort = this.sort;
    return this;
  }

  // Filtering
  public setFilters(filters: Record<string, unknown>): this {
    this.filter = filters;
    this.data.filter = this.filter;
    return this;
  }

  public addFilter(filter: string, value: unknown): this {
    this.filter[filter] = value;
    this.data.filter = this.filter;
    return this;
  }

  public removeFilter(filter: string): this {
    delete this.filter[filter];
    this.data.filter = this.filter;
    return this;
  }

  public clearFilters(): this {
    this.filter = {};
    this.data.filter = this.filter;
    return this;
  }

  // Fields
  public addFields(fields: string): this {
    this.fields.push(fields);
    this.data.fields = uniq(this.fields);
    return this;
  }

  public removeFields(fields: string): this {
    this.fields.findIndex(value => value === fields);
    return this;
  }

  public clearFields(): this {
    this.fields = [];
    return this;
  }
}
