<?php

declare(strict_types=1);

namespace App\Core\Image;

interface ImageInterface
{
    /** @return non-empty-string */
    public function getImageId(): string;

    /** @return non-empty-string */
    public function getRequestedFormat(): string;

    public function getSource(): \SplFileObject;
}
