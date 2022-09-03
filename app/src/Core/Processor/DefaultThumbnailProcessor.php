<?php

declare(strict_types=1);

namespace App\Core\Processor;

use App\Core\Image\ImageInterface;
use App\Core\ResizeStrategy\ResizeStrategyInterface;
use App\Core\ResizeStrategy\SizeInterface;

final class DefaultThumbnailProcessor implements ThumbnailProcessorInterface
{
    /** {@inheritDoc} */
    public function __invoke(
        ImageInterface $image,
        ResizeStrategyInterface $strategy,
        SizeInterface $size
    ): string {
        return $strategy->resize($image, $size);
    }
}
