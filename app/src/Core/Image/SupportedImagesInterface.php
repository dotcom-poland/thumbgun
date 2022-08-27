<?php

namespace App\Core\Image;

interface SupportedImagesInterface
{
    public function isSupported(string $requestedFormat): bool;
}
