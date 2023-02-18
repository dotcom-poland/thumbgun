<?php

declare(strict_types=1);

namespace App\Core\Security;

use App\Core\RequestContextInterface;

/** @psalm-pure */
interface ChecksumBuilderInterface
{
    public function __invoke(KeyInterface $key, RequestContextInterface $context): string;
}
