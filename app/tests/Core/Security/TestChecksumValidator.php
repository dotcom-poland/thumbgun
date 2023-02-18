<?php

declare(strict_types=1);

namespace Test\App\Core\Security;

use App\Core\RequestContextInterface;
use App\Core\Security\ChecksumValidatorInterface;

final class TestChecksumValidator implements ChecksumValidatorInterface
{
    public function __invoke(RequestContextInterface $context): bool
    {
        $string = \sprintf(
            '%s:%s:%s:%s',
            $context->getStrategyName(),
            $context->getSizeFormat(),
            $context->getImageId(),
            $context->getImageFormat(),
        );

        return $string === $context->getChecksum();
    }
}
