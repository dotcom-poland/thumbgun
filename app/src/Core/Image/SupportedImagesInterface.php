<?php

namespace App\Core\Image;

use App\Core\RequestContextInterface;

interface SupportedImagesInterface
{
    public function isSupported(RequestContextInterface $context): bool;
}
