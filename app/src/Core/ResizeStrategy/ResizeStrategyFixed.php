<?php

declare(strict_types=1);

namespace App\Core\ResizeStrategy;

use App\Core\Filesystem\Exception\FilesystemException;
use App\Core\Filesystem\FileFactory;
use App\Core\Image\ImageInterface;
use App\Core\ResizeStrategy\Exception\ResizeException;

final class ResizeStrategyFixed implements ResizeStrategyInterface
{
    public function __construct (
        private readonly FileFactory $filesystem,
    ) {}

    /** {@inheritDoc} */
    public function __invoke(ImageInterface $image, SizeInterface $size): \SplFileObject
    {
        if (false === ($size instanceof SizeRectangle)) {
            throw new ResizeException();
        }

        $imageFilename = \sprintf('%s.final.%s', $image->getImageId(), $image->getRequestedFormat());

        try {
            $im = new \Imagick($image->getSource()->getPathname());
            $im->setImageFormat($image->getRequestedFormat());
            $im->thumbnailImage($size->getWidth(), $size->getHeight());
        } catch (\ImagickException $exception) {
            throw new ResizeException(
                \sprintf('Imagick: %s', $exception->getMessage()),
                $exception->getCode(),
                $exception,
            );
        }

        try {
            $resizedImage = ($this->filesystem)($imageFilename, $im->getImageBlob());
        } catch (FilesystemException $exception) {
            throw new ResizeException(
                'Could not create temporary image',
                (int) $exception->getCode(),
                $exception,
            );
        } catch (\ImagickException $exception) {
            throw new ResizeException(
                \sprintf('Imagick: %s', $exception->getMessage()),
                $exception->getCode(),
                $exception,
            );
        }

        return $resizedImage;
    }
}
