<?php

namespace App\Http\Controllers\Servers;

use App\DataObjects\ServerData;
use App\Exceptions\NoStorageServiceConfiguredException;
use App\Exceptions\SshException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Servers\ServerUpdateRequest;
use App\Models\Server;
use App\Repositories\Servers\ServerUpdateRepository;
use App\Services\IconService;
use App\Services\ServerSshService;
use Illuminate\Http\JsonResponse;
use League\Flysystem\FilesystemException;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ServerUpdateController extends Controller
{
    public function __construct(
        private readonly ServerUpdateRepository $updateRepository,
        private readonly ServerSshService $sshService,
        private readonly IconService $iconService,
    ) {
    }

    /**
     * TODO: Type is immutable
     *
     * @throws Throwable
     */
    public function __invoke(
        int $id,
        ServerUpdateRequest $request,
    ): JsonResponse {
        $server = $request->getServer();

        $data = $request->validated();

        if ($request->hasFile('icon_file')) {
            $data['icon'] = $this->iconService->storeServerIcon($request->files->get('icon_file'));
        }

        try {
            $server = $this->updateRepository->update($server, $data);
            try {
                $this->pingSsh($server);

                $this->putServerProperties($server, $request->get('server_properties'));
            } catch (FilesystemException $e) {
                throw new RuntimeException('errors.server.invalid_filesystem_connection', previous: $e);
            } catch (SshException $e) {
                throw new RuntimeException('errors.server.invalid_ssh_connection', previous: $e);
            } catch (NoStorageServiceConfiguredException $e) {
                throw new RuntimeException('errors.server.not_enough_connection_for_storage');
            }
        } catch (RuntimeException $e) {
            if (array_key_exists('icon', $data) && $data['icon']) {
                $this->iconService->deleteIcon($data['icon']);
            }

            return new JsonResponse([
                'errors'  => $e->getMessage(),
                'context' => $e->getPrevious()?->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return new JsonResponse(ServerData::from($server));
    }

    /**
     * @throws SshException
     */
    private function pingSsh(Server $server): void
    {
        if (!$this->sshService->ping($server)) {
            throw new SshException('Can\'t ping SSH connection');
        }
    }

    /**
     * @throws FilesystemException
     * @throws SshException
     */
    private function putServerProperties(Server $server, string $inputProperties): void
    {
        $server->storage_service->put(
            $server,
            'server.properties',
            file_get_contents(base_path('static/default_server.properties')) . $inputProperties
        );
    }
}
