<?php

declare(strict_types=1);

namespace App\Core\Source;

use App\Core\Filesystem\FileFactory;
use App\Core\Image\ImageInterface;
use App\Core\Image\ImmutableImage;
use App\Core\Source\Exception\ImageSourceException;

final class RandomImageSource implements ImageSourceInterface
{
    public function __construct (
        private readonly FileFactory $filesystem,
    ) {}

    /** {@inheritDoc} */
    public function __invoke(string $imageId, string $imageFormat): ImageInterface
    {
        $randomImageUrl = \sprintf('https://picsum.photos/seed/%s/1000/1000', \sha1($imageId));
        $randomImageData = \file_get_contents($randomImageUrl);

        if (!$randomImageData) {
            throw new ImageSourceException('Could not download the image');
        }

        $randomId = \md5(\uniqid((string) \random_int(0, 1000000), true));
        $file = ($this->filesystem)(\sprintf('%s.random.jpeg', $randomId), $randomImageData);

        return new ImmutableImage($imageId, $imageFormat, $file);
    }
}
