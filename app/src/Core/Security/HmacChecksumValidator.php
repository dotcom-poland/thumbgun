<?php

declare(strict_types=1);

namespace App\Core\Security;

use App\Core\RequestContextInterface;

/** {@inheritDoc} */
final class HmacChecksumValidator implements ChecksumValidatorInterface
{
    public function __construct(
        private readonly ChecksumBuilderInterface $hashBuilder,
        private readonly KeyVaultInterface $vault,
    ) {
    }

    public function __invoke(RequestContextInterface $context): bool
    {
        foreach ($this->vault as $key) {
            $hash = ($this->hashBuilder)($key, $context);

            if (\hash_equals($hash, $context->getChecksum())) {
                return true;
            }
        }

        return false;
    }
}
