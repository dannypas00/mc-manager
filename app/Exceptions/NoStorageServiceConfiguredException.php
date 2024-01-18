<?php

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * @codeCoverageIgnore No need to test a single message
 */
class NoStorageServiceConfiguredException extends Exception
{
    public function __construct(int $serverId, ?Throwable $previous = null)
    {
        parent::__construct("Server $serverId doesn't have enough information to start a storage service", 0, $previous);
    }
}
