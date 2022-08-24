<?php

namespace App\Core\Security;

/**
 * @psalm-pure
 */
interface ChecksumValidatorInterface
{
    public function __invoke(string $strategy, string $size, string $group, string $imageId, string $checksum): bool;
}
