import i18n from "../../i18n";
import { FontAwesomeIconProps } from "../Icons/FontAwesomeIconProps";
import { QueryBuilderIndexRequest } from "../../Communication/Base/QueryBuilderIndexRequest";

export type DateRangeValue = {
  start: string | undefined;
  end: string | undefined;
};

export enum FilterType {
  Search,
  Select,
  RemoteSelect,
  Date,
}

type BaseFilterOption = {
  // Name of the filter to set on request
  filter: string;
  type: FilterType;
};

export type SearchFilterOption = BaseFilterOption & {
  // Placeholder for input box
  placeholder?: string;
  type: FilterType.Search;
};

export type SelectFilterOption<T extends Record<string, unknown>> =
  BaseFilterOption & {
    options?: T[];
    label?: (option: T) => string;
    value?: (option: T) => string;
    type: FilterType.Select;
  };

export type RemoteSelectFilterOptions<T extends Record<string, unknown>> = {
  request: QueryBuilderIndexRequest<T>;
  type: FilterType.RemoteSelect;
};

export enum DateFilterType {
  DateRange,
  TimeRange,
  SingleDate,
  SingleTime,
}

export type DateFilterOption<T extends Record<string, unknown>> =
  BaseFilterOption & {
    transformDate?: (date: Date, entry: T) => Date | string | number;
    type: FilterType.Date;
    startDate?: Date;
    endDate?: Date;
    dateFilterType: DateFilterType;
  };

export type FilterOption<
  T extends Record<string, unknown> = Record<string, unknown>,
> = BaseFilterOption &
  (
    | SearchFilterOption
    | SelectFilterOption<T>
    | RemoteSelectFilterOptions<T>
    | DateFilterOption<T>
  );

export type TableHeader<T extends Record<string, unknown>> = {
  title: string;
  key: string;
  width?: number;
  bodySlot?: string;
  headerSlot?: string;
  filter?: FilterOption<T>;
};

export type BulkOption<T extends Record<string, unknown>> = {
  title: string;
  onClick: (selected: T[]) => void;
  unselectAfter?: boolean;
  icon?: FontAwesomeIconProps;
  classes?: string;
  confirmation?: true;
  confirmationText?: ((selected: T[]) => string) | string;
};

export const IdHeader = {
  key: "id",
  title: i18n.global.t("components.datatable.id_title"),
};

export const CreatedAtHeader = {
  key: "created_at",
  title: i18n.global.t("components.datatable.created_at_title"),
  filter: {
    type: FilterType.Date,
    filter: "created_at",
    dateFilterType: DateFilterType.DateRange,
  } as DateFilterOption<Record<string, unknown>>,
};

export const UpdatedAtHeader = {
  key: "updated_at",
  title: i18n.global.t("components.datatable.updated_at_title"),
  filter: {
    type: FilterType.Date,
    filter: "updated_at",
    dateFilterType: DateFilterType.DateRange,
  } as DateFilterOption<Record<string, unknown>>,
};
