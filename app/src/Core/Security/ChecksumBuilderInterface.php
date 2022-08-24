<?php

declare(strict_types=1);

namespace App\Core\Security;

/** @psalm-pure */
interface ChecksumBuilderInterface
{
    public function __invoke(string $strategy, string $size, string $group, string $imageId, string $key): string;
}
