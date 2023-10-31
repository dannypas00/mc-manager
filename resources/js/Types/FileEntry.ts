export interface FileEntry {
  type: 'dir' | 'file',
  path: string,
  visibility: 'public' | 'private',
  last_modifier: null | Date,
  extra_metadata: Array<unknown>
}
