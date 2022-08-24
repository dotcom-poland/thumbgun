<?php

declare(strict_types=1);

namespace App\Core\ResizeStrategy;

use App\Core\Filesystem\Exception\FilesystemException;
use App\Core\Filesystem\FilesystemInterface;
use App\Core\Image\ImageInterface;
use App\Core\ResizeStrategy\Exception\ResizeException;

final class ResizeStrategyFixed implements ResizeStrategyInterface
{
    public function __construct (
        private readonly FilesystemInterface $filesystem,
    ) {}

    /** {@inheritDoc} */
    public function __invoke(ImageInterface $image, SizeInterface $size): \SplFileInfo
    {
        if (false === ($size instanceof SizeRectangle)) {
            throw new ResizeException();
        }

        $imageName = \sprintf(
            '%s/%s.final.%s',
            $image->getImageGroup(),
            $image->getImageId(),
            $image->getRequestedFormat(),
        );

        try {
            $file = $this->filesystem->createFile($imageName);
        } catch (FilesystemException $exception) {
            throw new ResizeException(
                'Could not create temporary image',
                (int) $exception->getCode(),
                $exception,
            );
        }

        try {
            $im = new \Imagick($image->getSource()->getPathname());
            $im->thumbnailImage($size->getWidth(), $size->getHeight());
            $im->writeImage(\sprintf('%s:%s', $image->getRequestedFormat(), $file->getPathname()));
        } catch (\ImagickException $exception) {
            throw new ResizeException(
                \sprintf('Imagick: %s', $exception->getMessage()),
                $exception->getCode(),
                $exception,
            );
        }

        return $file->getFileInfo();
    }
}
