<?php

namespace App\Http\Requests\Servers;

use App\Enums\ServerType;
use App\Models\Server;
use App\Repositories\Servers\FrontendServerShowRepository;
use Illuminate\Foundation\Http\FormRequest;

class ServerUpdateRequest extends FormRequest
{
    private Server $model;

    private const SSH_RULES = [
        'public_ip'    => 'required|string',
        'port'         => 'required|integer|min:0|max:65535',
        'rcon_port'    => 'required|integer|min:0|max:65535',
        'local_ip'     => 'required|string',
        'enable_ssh'   => 'required|boolean|accepted_if:enable_ftp,false',
        'ssh_port'     => 'required|integer|min:0|max:65535',
        'ssh_username' => 'required|string',
        'ssh_key'      => 'sometimes|nullable|string',
    ];

    private const CONNECTION_RULES = [
        'enable_ftp'   => 'required|boolean|accepted_if:enable_ssh,false',
        'is_sftp'      => 'required|boolean',
        'ftp_port'     => 'required|integer|min:0|max:65535',
        'ftp_username' => 'required|string',
        'ftp_password' => 'required|string'
    ];

    private const MINECRAFT_RULES = [
        'version'             => 'required_if:is_custom,false|string',
        'is_custom'           => 'required|boolean',
        'custom_jar'          => 'sometimes|nullable|file|mimes:application/java-archive',
        'custom_docker_image' => 'sometimes|nullable|string',
        'server_properties'   => 'sometimes|nullable|string'
    ];

    /**
     * @codeCoverageIgnore It's just validation
     */
    public function rules(): array
    {
        $rules = [
                'enabled'     => 'required|boolean',
                'name'        => 'required|string',
                'description' => 'sometimes|nullable|string',
                'icon_file'   => 'sometimes|nullable|image',
            ] + match ($this->getServer()->type) {
                ServerType::Manual => self::SSH_RULES + self::CONNECTION_RULES + self::MINECRAFT_RULES,
                ServerType::Installed => self::SSH_RULES + self::MINECRAFT_RULES,
                ServerType::Managed => self::MINECRAFT_RULES,
            };

        if ($rules['ssh_key'] && !$this->getServer()->is_ssh_key_filled) {
            $rules['ssh_key'] = 'required|string';
        }

        return $rules;
    }

    public function getServer(): Server
    {
        if (!isset($this->model)) {
            $this->model = app(FrontendServerShowRepository::class)->show($this->route('id'));
        }

        return $this->model;
    }
}
