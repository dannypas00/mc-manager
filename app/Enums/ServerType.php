<?php

namespace App\Enums;

enum ServerType: int
{
    // User manually downloads and installs all required files
    // User then provides run command
    case Manual = 1;

    // MC-manager downloads all required files to server
    // If ssh is available, MC-manager will attempt to install
    case Installed = 2;

    // TODO: Managed servers
    case Managed = 3;
}
