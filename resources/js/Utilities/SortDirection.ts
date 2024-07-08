export enum SortDirection {
  Asc = "asc",
  Desc = "desc",
  None = null,
}

export function nextSortDirection(direction: SortDirection): SortDirection {
  switch (direction) {
    case SortDirection.Asc:
      return SortDirection.Desc;
    case SortDirection.Desc:
      return SortDirection.None
    case SortDirection.None:
      return SortDirection.Asc;
  }
}
