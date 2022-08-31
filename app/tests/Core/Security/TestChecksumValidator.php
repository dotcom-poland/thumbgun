<?php

declare(strict_types=1);

namespace Test\App\Core\Security;

use App\Core\Security\ChecksumValidatorInterface;

final class TestChecksumValidator implements ChecksumValidatorInterface
{
    public function __invoke(
        string $strategy,
        string $size,
        string $imageId,
        string $format,
        string $checksum,
    ): bool {
        return "$strategy:$size:$imageId:$format" === $checksum;
    }
}
