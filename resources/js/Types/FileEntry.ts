export interface FileEntry {
  type: 'dir' | 'file',
  path: string,
  visibility: 'public' | 'private',
  last_modified: null | Date,
  extra_metadata: Array<unknown>
  file_size: number,
  mime_type: string,
}
