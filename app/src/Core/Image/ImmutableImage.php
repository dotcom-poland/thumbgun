<?php

declare(strict_types=1);

namespace App\Core\Image;

use App\Core\Image\Exception\ImageException;

final class ImmutableImage implements ImageInterface
{
    public const IMAGE_ID_PROHIBITED_SYMBOLS = [
        '~', '`', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')',
        '+', '=', '[', ']', '{', '}', ':', ';', '<', '>', '?', '/', '\\',
    ];

    /** @var non-empty-string */
    private readonly string $imageId;

    /** @var non-empty-string */
    private readonly string $requestedFormat;

    private readonly \SplFileObject $source;

    /** @throws ImageException */
    public function __construct(string $imageId, string $requestedFormat, \SplFileObject $source)
    {
        if (empty($imageId) || self::containsDangerousSymbols($imageId)) {
            throw new ImageException('Image id empty');
        }

        if (empty($requestedFormat)) {
            throw new ImageException('Requested format empty');
        }

        $this->imageId = $imageId;
        $this->requestedFormat = $requestedFormat;
        $this->source = $source;
    }

    private static function containsDangerousSymbols(string $imageId): bool
    {
        $symbolsDetected = \array_intersect(self::IMAGE_ID_PROHIBITED_SYMBOLS, \str_split($imageId));

        return \count($symbolsDetected) > 0;
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

    public function getSource(): \SplFileObject
    {
        return $this->source;
    }
}
