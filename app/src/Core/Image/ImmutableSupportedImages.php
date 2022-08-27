<?php

declare(strict_types=1);

namespace App\Core\Image;

final class ImmutableSupportedImages implements SupportedImagesInterface
{
    /**
     * @param string[] $supportedOutputFormats
     */
    public function __construct(
        private readonly array $supportedOutputFormats
    ) {}

    public function isSupported(string $requestedFormat): bool
    {
        return \in_array($requestedFormat, $this->supportedOutputFormats, true);
    }
}
