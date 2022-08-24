<?php

declare(strict_types=1);

namespace App\Core\Image;

use App\Core\Image\Exception\ImageException;

final class ImmutableImage implements ImageInterface
{
    /** @var non-empty-string */
    private readonly string $imageGroup;

    /** @var non-empty-string */
    private readonly string $imageId;

    /** @var non-empty-string */
    private readonly string $requestedFormat;

    private readonly \SplFileInfo $source;

    /** @throws ImageException */
    public function __construct(string $imageGroup, string $imageId, string $requestedFormat, \SplFileInfo $source)
    {
        if (empty($imageGroup)) {
            throw new ImageException('Group empty');
        }

        if (empty($imageId)) {
            throw new ImageException('Image id empty');
        }
        if (!preg_match('/^[a-zA-Z0-9\-_.]+$/', $imageId)) {
            throw new ImageException('Image id invalid');
        }

        if (empty($requestedFormat)) {
            throw new ImageException('Requested format empty');
        }

        $this->imageGroup = $imageGroup;
        $this->imageId = $imageId;
        $this->requestedFormat = $requestedFormat;
        $this->source = $source;
    }

    /** {@inheritDoc} */
    public function getImageGroup(): string
    {
        return $this->imageGroup;
    }

    /** {@inheritDoc} */
    public function getImageId(): string
    {
        return $this->imageId;
    }

    /** {@inheritDoc} */
    public function getRequestedFormat(): string
    {
        return $this->requestedFormat;
    }

    public function getSource(): \SplFileInfo
    {
        return $this->source;
    }
}
