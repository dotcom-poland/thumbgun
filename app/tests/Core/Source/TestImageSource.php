<?php

declare(strict_types=1);

namespace Test\App\Core\Source;

use App\Core\Image\ImageInterface;
use App\Core\Image\ImmutableImage;
use App\Core\RequestContextInterface;
use App\Core\Source\ImageSourceInterface;

final class TestImageSource implements ImageSourceInterface
{
    public function __construct (
        private ?\Throwable $exception = null
    ) {}

    /** {@inheritDoc} */
    public function __invoke(RequestContextInterface $context): ImageInterface
    {
        if ($this->exception) {
            throw $this->exception;
        }

        return new ImmutableImage($context->getImageId(), $context->getImageFormat(), static function (): string {
            return \file_get_contents(__DIR__ . '/tiny.jpg');
        });
    }

    public function setThrownException(?\Throwable $exception): void
    {
        $this->exception = $exception;
    }
}
