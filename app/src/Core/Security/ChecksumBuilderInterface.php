<?php

declare(strict_types=1);

namespace App\Core\Security;

/** @psalm-pure */
interface ChecksumBuilderInterface
{
    public function __invoke(
        KeyInterface $key,
        string $strategy,
        string $size,
        string $imageId,
        string $format,
    ): string;
}
