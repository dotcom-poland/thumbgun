<?php

declare(strict_types=1);

namespace App\Core\Image;

final class ImmutableSupportedImages implements SupportedImagesInterface
{
    /**
     * @param string[] $supportedFormats
     */
    public function __construct(
        private readonly array $supportedFormats
    ) {}

    public function isSupported(string $requestedFormat): bool
    {
        return \in_array($requestedFormat, $this->supportedFormats, true);
    }
}
