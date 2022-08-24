<?php

namespace App\Core\Source;

use App\Core\Image\Exception\ImageException;
use App\Core\Image\ImageInterface;
use App\Core\Source\Exception\ImageNotFoundException;
use App\Core\Source\Exception\SourceException;

interface SourceInterface
{
    /**
     * @throws ImageException
     * @throws ImageNotFoundException
     * @throws SourceException
     */
    public function __invoke(string $imageGroup, string $imageId, string $imageFormat): ImageInterface;
}
