<?php

namespace App\Enums;

enum ServerStatus: string
{
    case Unknown = 'unknown';
    case Error = 'error';
    case Down = 'down';
    case Starting = 'starting';
    case Up = 'up';
    case Stopping = 'stopping';
}
