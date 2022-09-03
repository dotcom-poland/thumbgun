<?php

declare(strict_types=1);

namespace App\Core\Source;

use App\Core\Image\ImageInterface;
use App\Core\Image\ImmutableImage;

final class CachingImageSourceDecorator implements ImageSourceInterface
{
    public function __construct(
        private readonly ImageSourceInterface $source,
        private readonly string $rootDirectory,
        private readonly string $storageDirectory,
    ) {}

    /** {@inheritDoc} */
    public function __invoke(string $imageId, string $imageFormat): ImageInterface
    {
        return new ImmutableImage($imageId, $imageFormat, function () use ($imageId, $imageFormat): string {
            $imagePath = \sprintf(
                '%s/%s/%s',
                $this->rootDirectory,
                $this->storageDirectory,
                $imageId,
            );

            if (\file_exists($imagePath)) {
                return \file_get_contents($imagePath);
            }

            \mkdir(\dirname($imagePath), 0777, true);

            $remoteImage = ($this->source)($imageId, $imageFormat);

            \file_put_contents($imagePath, $remoteImage->getSource()());

            return \file_get_contents($imagePath);
        });
    }
}
