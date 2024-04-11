import i18n from "../../i18n";
import { FontAwesomeIconProps } from "../Icons/FontAwesomeIconProps";

export type TableHeader = {
  title: string;
  key: string;
  width?: number;
  bodySlot?: string;
  headerSlot?: string;
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
