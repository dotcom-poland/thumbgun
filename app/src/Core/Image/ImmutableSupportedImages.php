<?php

declare(strict_types=1);

namespace App\Core\Image;

use App\Core\RequestContextInterface;

final class ImmutableSupportedImages implements SupportedImagesInterface
{
    /**
     * @param string[] $supportedOutputFormats
     */
    public function __construct(
        private readonly array $supportedOutputFormats,
    ) {}

    public function isSupported(RequestContextInterface $context): bool
    {
        return \in_array($context->getImageFormat(), $this->supportedOutputFormats, true);
    }
}
