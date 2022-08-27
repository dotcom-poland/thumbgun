<?php

declare(strict_types=1);

namespace Test\App\Core\Source;

use App\Core\Image\ImageInterface;
use App\Core\Image\ImmutableImage;
use App\Core\Source\ImageSourceInterface;

final class TestImageSource implements ImageSourceInterface
{
    public function __construct (
        private readonly \SplFileObject $image,
        private ?\Throwable $exception = null
    ) {}

    /** {@inheritDoc} */
    public function __invoke(string $imageId, string $imageFormat): ImageInterface
    {
        if ($this->exception) {
            throw $this->exception;
        }

        return new ImmutableImage($imageId, $imageFormat, $this->image);
    }

    public function setThrownException(?\Throwable $exception): void
    {
        $this->exception = $exception;
    }
}
