export type ServerData = {
  id: number;
  enabled: boolean;
  type: ServerType;
  status: ServerStatus;
  port: number;
  rcon_port: number;
  enable_ftp: boolean;
  is_sftp: boolean;
  use_ssh_auth: boolean;
  ftp_port: number | null;
  ftp_host: string | null;
  ftp_username: string | null;
  enable_ssh: boolean;
  ssh_username: string | null;
  ssh_port: number | null;
  current_players: number;
  maximum_players: number;
  name: string;
  description: string | null;
  version: string | null;
  icon: string;
  local_ip: string | null;
  public_ip: string | null;
  has_accepted_eula: boolean;
  player_list: Array<any>;
  users: Array<any> | null;
  is_ssh_key_filled: boolean;
  created_at: string | null;
  updated_at: string | null;
  rcon_password: string | null;
  ssh_key: string | null;
  ftp_password: string | null;
};
export enum ServerStatus {
  'Unknown' = 'unknown',
  'Error' = 'error',
  'Down' = 'down',
  'Starting' = 'starting',
  'Up' = 'up',
  'Stopping' = 'stopping',
}
export enum ServerType {
  'Manual' = 1,
  'Installed' = 2,
  'Managed' = 3,
}
export type UserData = {
  id: number;
  name: string;
  email: string;
  profile_photo_url: string | null;
  email_verified_at: string | null;
  created_at: string | null;
  updated_at: string | null;
  servers: Array<any> | null;
};
