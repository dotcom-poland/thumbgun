<?php

namespace App\Core\Security;

use App\Core\RequestContextInterface;

/**
 * @psalm-pure
 */
interface ChecksumValidatorInterface
{
    public function __invoke(RequestContextInterface $context): bool;
}
