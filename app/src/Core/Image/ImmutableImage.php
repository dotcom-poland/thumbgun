<?php

declare(strict_types=1);

namespace App\Core\Image;

use App\Core\Image\Exception\ImageException;
use Closure;

final class ImmutableImage implements ImageInterface
{
    public const IMAGE_ID_PROHIBITED_SYMBOLS = [
        '~', '`', '!', '@', '#', '$', '%', '^', '*', '(', ')',
        '[', ']', '{', '}', ':', ';', '<', '>', '?', '\\',
    ];

    /** @var non-empty-string */
    private readonly string $imageId;

    /** @var non-empty-string */
    private readonly string $requestedFormat;

    /** @var Closure():string */
    private readonly Closure $source;

    /**
     * @param Closure():string $source
     *
     * @throws ImageException
     */
    public function __construct(string $imageId, string $requestedFormat, Closure $source)
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

    /** {@inheritDoc} */
    public function getSource(): Closure
    {
        return $this->source;
    }
}
