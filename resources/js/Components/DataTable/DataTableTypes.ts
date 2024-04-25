import i18n from "../../i18n";
import { FontAwesomeIconProps } from "../Icons/FontAwesomeIconProps";
import { QueryBuilderIndexRequest } from '../../Communication/Base/QueryBuilderIndexRequest';

type BaseFilterOption = {
  // Name of the filter to set on request
  filter: string;
}

export type SearchFilterOption = BaseFilterOption & {
  // Placeholder for input box
  placeholder?: string;
};

export type SelectFilterOption<T extends Record<string, never>> = BaseFilterOption & {
  options?: T[],
  label?: (option: T) => string,
  value?: (option: T) => string,
}

export type RemoteSelectFilterOptions<T extends Record<string, never>> = {
  request: QueryBuilderIndexRequest<T>,
}

export type DateFilterOption<T extends Record<string, never>> = {
  transformDate?: (date: Date, entry: T) => Date|string|number
}

export type FilterOption<T extends Record<string, never> = Record<string, never>> = boolean | SearchFilterOption | SelectFilterOption<T> | RemoteSelectFilterOptions<T> | DateFilterOption;

export type TableHeader<T extends Record<string, never>> = {
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

export const IdHeader: TableHeader = {
  key: "id",
  title: i18n.global.t("components.datatable.id_title"),
};

export const CreatedAtHeader = {
  key: "created_at",
  title: i18n.global.t("components.datatable.created_at_title"),
};

export const UpdatedAtHeader = {
  key: "updated_at",
  title: i18n.global.t("components.datatable.updated_at_title"),
};
