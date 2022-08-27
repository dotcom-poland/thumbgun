<?php

declare(strict_types=1);

namespace App\Core\Processor;

use App\Core\Image\ImageInterface;
use App\Core\ResizeStrategy\ResizeStrategyInterface;
use App\Core\ResizeStrategy\SizeInterface;

interface ThumbnailProcessorInterface
{
    /**
     * @throws \Exception Upon any error
     */
    public function __invoke(
        ImageInterface $image,
        ResizeStrategyInterface $strategy,
        SizeInterface $size
    ): \SplFileObject;
}
