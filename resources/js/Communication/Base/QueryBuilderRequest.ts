import { Request } from "./Request";
import { uniq } from "lodash";

type QueryBuilderData = {
  filter?: Record<string, string | number>;
  include?: string[];
  sort?: string[];
  fields?: string[];
};

export type QueryBuilderIndexData<T extends Array<Record<string, unknown>>> = {
  data: T;
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

type QueryBuilderResponseData<T> = T | QueryBuilderIndexData<T>;

export abstract class QueryBuilderRequest<
  T,
  D = Record<string, never>,
> extends Request<T & QueryBuilderResponseData<T>, D & QueryBuilderData> {
  private filter: Record<string, string | number> = {};
  private sort: string[] = [];
  private include: string[] = [];
  private fields: string[] = ["*"];

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
      (entry) => entry === include || entry === `-${include}`,
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
  public setSort(sorting: string[]): this {
    this.sort = sorting;
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
      (entry) => entry === sorting || entry === `-${sorting}`,
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
  public setFilters(filters: Record<string, string | number>): this {
    this.filter = filters;
    this.data.filter = this.filter;
    return this;
  }

  public addFilter(filter: string, value: string | number): this {
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
    this.fields.findIndex((value) => value === fields);
    return this;
  }

  public clearFields(): this {
    this.fields = [];
    return this;
  }
}
