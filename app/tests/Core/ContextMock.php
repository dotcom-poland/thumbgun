<?php

declare(strict_types=1);

namespace Test\App\Core;

use App\Core\RequestContextInterface;

final class ContextMock implements RequestContextInterface
{
    public function __construct(
        private readonly string $checksum = 'checksum',
        private readonly string $strategyName = 'fixed',
        private readonly string $sizeFormat = '768x400',
        private readonly string $imageFormat = 'cats_and_mices',
        private readonly string $imageId = 'image-id.jpg',
    ) {
    }

    public function getChecksum(): string
    {
        return $this->checksum;
    }

    public function getStrategyName(): string
    {
        return $this->strategyName;
    }

    public function getSizeFormat(): string
    {
        return $this->sizeFormat;
    }

    public function getImageFormat(): string
    {
        return $this->imageFormat;
    }

    public function getImageId(): string
    {
        return $this->imageId;
    }
}
