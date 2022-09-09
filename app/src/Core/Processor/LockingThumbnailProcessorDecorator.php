<?php

declare(strict_types=1);

namespace App\Core\Processor;

use App\Core\Image\ImageInterface;
use App\Core\ResizeStrategy\ResizeStrategyInterface;
use App\Core\ResizeStrategy\SizeInterface;
use Symfony\Component\Lock\LockFactory;

final class LockingThumbnailProcessorDecorator implements ThumbnailProcessorInterface
{
    public function __construct(
        private readonly ThumbnailProcessorInterface $processor,
        private readonly LockFactory $lockFactory,
    ) {}

    /** {@inheritDoc} */
    public function __invoke(
        ImageInterface $image,
        ResizeStrategyInterface $strategy,
        SizeInterface $size,
    ): string {
        $lock = $this->lockFactory->createLock('image.' . \crc32($image->getImageId()));
        $lock->acquire(true);

        try {
            return ($this->processor)($image, $strategy, $size);
        } finally {
            $lock->release();
        }
    }
}
