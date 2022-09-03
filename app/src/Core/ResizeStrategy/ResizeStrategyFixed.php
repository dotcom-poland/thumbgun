<?php

declare(strict_types=1);

namespace App\Core\ResizeStrategy;

use App\Core\Image\ImageInterface;

final class ResizeStrategyFixed implements ResizeStrategyInterface
{
    /** {@inheritDoc} */
    public function resize(ImageInterface $image, SizeInterface $size): string
    {
        if (false === ($size instanceof SizeRectangle)) {
            throw new \InvalidArgumentException(\sprintf(
                'Expected %s got %s',
                SizeRectangle::class,
                \get_class($size),
            ));
        }

        $im = new \Imagick();
        $im->readImageBlob($image->getSource()());
        $im->setImageFormat($image->getRequestedFormat());
        $im->thumbnailImage($size->getWidth(), $size->getHeight());

        try {
            return $im->getImageBlob();
        } finally {
            $im->clear();
        }
    }

    public function toString(): string
    {
        return 'fixed';
    }
}
