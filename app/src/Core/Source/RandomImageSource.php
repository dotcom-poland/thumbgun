<?php

declare(strict_types=1);

namespace App\Core\Source;

use App\Core\Image\ImageInterface;
use App\Core\Image\ImmutableImage;
use App\Core\Source\Exception\ImageSourceException;

final class RandomImageSource implements ImageSourceInterface
{
    /** {@inheritDoc} */
    public function __invoke(string $imageId, string $imageFormat): ImageInterface
    {
        $randomImageUrl = \sprintf('https://picsum.photos/seed/%s/1000/1000', \sha1($imageId));
        $randomImageData = \file_get_contents($randomImageUrl);

        if (!$randomImageData) {
            throw new ImageSourceException('Could not download the image');
        }

        $file = new \SplTempFileObject();
        $file->fwrite($randomImageData);
        $file->fseek(0);

        return new ImmutableImage($imageId, $imageFormat, $file);
    }
}
