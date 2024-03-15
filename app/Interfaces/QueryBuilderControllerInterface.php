<?php

namespace App\Interfaces;

interface QueryBuilderControllerInterface
{
    public function getDataObject(): string;
    public function getSettings(): SettingsInterface;
}
