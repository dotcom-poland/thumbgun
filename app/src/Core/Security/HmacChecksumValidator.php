<?php

declare(strict_types=1);

namespace App\Core\Security;

/** {@inheritDoc} */
final class HmacChecksumValidator implements ChecksumValidatorInterface
{
    public function __construct(
        private readonly ChecksumBuilderInterface $hashBuilder,
        private readonly VaultInterface $vault,
    ) {}

    public function __invoke(string $strategy, string $size, string $imageId, string $checksum): bool
    {
        foreach ($this->vault as $key) {
            $hash = ($this->hashBuilder)($strategy, $size, $imageId, $key);

            if (\hash_equals($hash, $checksum)) {
                return true;
            }
        }

        return false;
    }
}
