import i18n from '../../i18n';

export type TableHeader = {
  title: string,
  key: string,
  width?: number,
  bodySlot?: string,
  headerSlot?: string,
}

export type BulkOption<T extends Record<string, any>> = {
  title: string,
  onClick: (selected: T[]) => void,
  unselectAfter?: boolean,
}

export const IdHeader: TableHeader = {
  key: 'id',
  title: i18n.global.t('components.datatable.id_title')
}

export const CreatedAtHeader = {
  key: 'created_at',
  title: i18n.global.t('components.datatable.created_at_title')
}

export const UpdatedAtHeader = {
  key: 'updated_at',
  title: i18n.global.t('components.datatable.updated_at_title')
}
