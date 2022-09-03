<?php

namespace App\Core\Source;

use App\Core\Image\Exception\ImageException;
use App\Core\Image\ImageInterface;
use App\Core\Source\Exception\ImageNotFoundException;
use Exception;

interface ImageSourceInterface
{
    /**
     * @throws ImageException
     * @throws ImageNotFoundException
     * @throws Exception
     */
    public function __invoke(string $imageId, string $imageFormat): ImageInterface;
}
