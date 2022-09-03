<?php

declare(strict_types=1);

namespace App\Core\ResizeStrategy;

use App\Core\Image\ImageInterface;
use Exception;

interface ResizeStrategyInterface
{
    public function toString(): string;

    /**
     * @throws Exception
     */
    public function resize(ImageInterface $image, SizeInterface $size): string;
}
