<?php

declare(strict_types=1);

namespace App\Core\Security;

/** {@inheritDoc} */
final class HmacChecksumValidator implements ChecksumValidatorInterface
{
    public function __construct(
        private readonly Sha1ChecksumBuilder $hashBuilder,
        private readonly VaultInterface $vault,
    ) {}

    public function __invoke(string $strategy, string $size, string $group, string $imageId, string $checksum): bool
    {
        foreach ($this->vault as $key) {
            $hash = ($this->hashBuilder)($strategy, $size, $group, $imageId, $key);

            if (\hash_equals($hash, $checksum)) {
                return true;
            }
        }

        return false;
    }
}
