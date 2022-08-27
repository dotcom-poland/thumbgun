<?php

declare(strict_types=1);

namespace App\Core\Security;

/** {@inheritDoc} */
final class DisabledValidator implements ChecksumValidatorInterface
{
    public function __invoke(string $strategy, string $size, string $imageId, string $checksum): bool
    {
        return true;
    }
}
