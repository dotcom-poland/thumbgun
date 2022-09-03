<?php

declare(strict_types=1);

namespace App\Core\ResizeStrategy;

use App\Core\Image\ImageInterface;
use App\Core\ResizeStrategy\Exception\ResizeException;

interface ResizeStrategyInterface
{
    public function toString(): string;

    /**
     * @throws ResizeException
     */
    public function resize(ImageInterface $image, SizeInterface $size): string;
}
