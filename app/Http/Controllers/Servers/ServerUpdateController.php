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
use Symfony\Component\HttpFoundation\File\UploadedFile;
use League\Flysystem\FilesystemException;
use RuntimeException;
use Str;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ServerUpdateController extends Controller
{
    public function __construct(
        private readonly ServerUpdateRepository $updateRepository,
        private readonly ServerSshService $sshService,
        private readonly IconService $iconService,
    ) {}

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
        // Show all hidden fields because they are needed for connections and we shouldn't be dumping them to the frontend
        $server = $server->makeVisible($server->getHidden());

        $data = $request->validated();

        if ($request->hasFile('icon_file')) {
            $data['icon'] = $this->iconService->storeServerIcon($request->files->get('icon_file'));
        }

        // Ensure ssh keys end with a newline, otherwise they won't work
        if ($request->has('ssh_key') && !Str::endsWith($data['ssh_key'], PHP_EOL)) {
            $data['ssh_key'] .= PHP_EOL;
        }

        try {
            $server = $this->updateRepository->update($server, $data);
            try {
                $this->pingSsh($server);

                // Store the server icon as the server-icon.png in the file root
                $this->putIconOnServer($server, $request->files->get('icon_file'));
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
    private function putIconOnServer(Server $server, ?UploadedFile $icon): void
    {
        if ($icon) {
            $server->storage_service->put($server, 'server-icon.png', $icon->getContent());
        }
    }
}
