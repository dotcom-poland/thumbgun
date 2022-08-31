<?php

namespace App\Core\Security;

/**
 * @psalm-pure
 */
interface ChecksumValidatorInterface
{
    public function __invoke(
        string $strategy,
        string $size,
        string $imageId,
        string $format,
        string $checksum,
    ): bool;
}
