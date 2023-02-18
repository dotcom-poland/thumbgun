<?php

declare(strict_types=1);

namespace App\Core\Source;

use App\Core\Image\ImageInterface;
use App\Core\Image\ImmutableImage;
use App\Core\RequestContextInterface;

final class CachingImageSourceDecorator implements ImageSourceInterface
{
    public function __construct(
        private readonly ImageSourceInterface $source,
        private readonly string $storage,
    ) {
    }

    /** {@inheritDoc} */
    public function __invoke(RequestContextInterface $context): ImageInterface
    {
        $imageId = $context->getImageId();
        $imageFormat = $context->getImageFormat();

        return new ImmutableImage($imageId, $imageFormat, function () use ($imageId, $context): string {
            $imagePath = \sprintf('%s/%s', $this->storage, $imageId);

            if (\file_exists($imagePath)) {
                return \file_get_contents($imagePath);
            }

            if (false === \is_dir(\dirname($imagePath))) {
                \mkdir(\dirname($imagePath), 0777, true);
            }

            $remoteImage = ($this->source)($context);

            \file_put_contents($imagePath, $remoteImage->getSource()());

            return \file_get_contents($imagePath);
        });
    }
}
