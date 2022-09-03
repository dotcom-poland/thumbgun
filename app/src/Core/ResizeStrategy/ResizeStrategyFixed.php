<?php

declare(strict_types=1);

namespace App\Core\ResizeStrategy;

use App\Core\Image\ImageInterface;
use App\Core\ResizeStrategy\Exception\ResizeException;

final class ResizeStrategyFixed implements ResizeStrategyInterface
{
    /** {@inheritDoc} */
    public function __invoke(ImageInterface $image, SizeInterface $size): \SplFileObject
    {
        if (false === ($size instanceof SizeRectangle)) {
            throw new ResizeException();
        }

        try {
            $im = new \Imagick();
            $im->readImageBlob($image->getSource()());
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
            $resizedImage = new \SplTempFileObject();
            $resizedImage->fwrite($im->getImageBlob());
            $resizedImage->fseek(0);
        } catch (\Exception $exception) {
            throw new ResizeException(
                $exception->getMessage(),
                (int) $exception->getCode(),
                $exception,
            );
        }

        return $resizedImage;
    }
}
