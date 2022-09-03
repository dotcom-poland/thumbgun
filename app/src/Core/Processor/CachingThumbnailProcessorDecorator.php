<?php

declare(strict_types=1);

namespace App\Core\Processor;

use App\Core\Image\ImageInterface;
use App\Core\ResizeStrategy\ResizeStrategyInterface;
use App\Core\ResizeStrategy\SizeInterface;

final class CachingThumbnailProcessorDecorator implements ThumbnailProcessorInterface
{
    public function __construct(
        private readonly ThumbnailProcessorInterface $processor,
        private readonly string $storage,
    ) {}

    /** {@inheritDoc} */
    public function __invoke(
        ImageInterface $image,
        ResizeStrategyInterface $strategy,
        SizeInterface $size
    ): string {
        $imagePath = \sprintf(
            '%s/%s/%s/%s/%s/%s',
            $this->storage,
            $strategy->toString(),
            $size->toString(),
            $image->getRequestedFormat(),
            \dirname($image->getImageId()),
            \basename($image->getImageId()),
        );

        if (\file_exists($imagePath)) {
            return \file_get_contents($imagePath);
        }

        if (false === \is_dir(\dirname($imagePath))) {
            \mkdir(\dirname($imagePath), 0777, true);
        }

        $blob = ($this->processor)($image, $strategy, $size);

        \file_put_contents($imagePath, $blob);

        return $blob;
    }
}
