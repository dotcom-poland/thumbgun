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
    ): \SplFileObject {
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
            return new \SplFileObject($imagePath);
        }

        if (false === \is_dir(\dirname($imagePath))) {
            \mkdir(\dirname($imagePath), 0777, true);
        }

        $resizedImage = ($this->processor)($image, $strategy, $size);

        $blob = '';
        while (!$resizedImage->eof()) {
            $blob .= $resizedImage->fgets();
        }

        \file_put_contents($imagePath, $blob);

        return new \SplFileObject($imagePath);
    }
}
