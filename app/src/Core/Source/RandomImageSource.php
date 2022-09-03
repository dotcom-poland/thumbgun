<?php

declare(strict_types=1);

namespace App\Core\Source;

use App\Core\Image\ImageInterface;
use App\Core\Image\ImmutableImage;

final class RandomImageSource implements ImageSourceInterface
{
    /** {@inheritDoc} */
    public function __invoke(string $imageId, string $imageFormat): ImageInterface
    {
        return new ImmutableImage($imageId, $imageFormat, static function () use ($imageId): string {
            $randomImageUrl = \sprintf('https://picsum.photos/seed/%s/1000/1000', \sha1($imageId));
            $randomImageData = \file_get_contents($randomImageUrl);

            if (!$randomImageData) {
                throw new \RuntimeException('Could not download random image from Picsum');
            }

            return $randomImageData;
        });
    }
}
