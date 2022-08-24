<?php

declare(strict_types=1);

namespace App\Core\Source;

use App\Core\Filesystem\Exception\FilesystemException;
use App\Core\Filesystem\FilesystemInterface;
use App\Core\Image\ImageInterface;
use App\Core\Image\ImmutableImage;
use App\Core\Source\Exception\ImageNotFoundException;
use App\Core\Source\Exception\SourceException;

final class RandomImageSource implements SourceInterface
{
    public function __construct (
        private readonly FilesystemInterface $filesystem,
    ) {}

    /** {@inheritDoc} */
    public function __invoke(string $imageGroup, string $imageId, string $imageFormat): ImageInterface
    {
        if ('random' !== $imageGroup) {
            throw new ImageNotFoundException();
        }

        $randomImageUrl = \sprintf('https://picsum.photos/seed/%s/1000/1000', $imageId);
        $randomImageData = \file_get_contents($randomImageUrl);

        if (!$randomImageData) {
            throw new SourceException('Could not download the image');
        }

        try {
            $file = $this->filesystem->createFile(\sprintf('%s/%s.random.jpg', $imageGroup, $imageId));
            $file->fwrite($randomImageData);
        } catch (FilesystemException $exception) {
            throw new SourceException(
                'Could not create temporary image',
                (int) $exception->getCode(),
                $exception,
            );
        }

        return new ImmutableImage($imageGroup, $imageId, $imageFormat, $file->getFileInfo());
    }
}
