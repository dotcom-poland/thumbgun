<?php

declare(strict_types=1);

namespace App\Core\Security;

/** {@inheritDoc} */
final class HmacChecksumValidator implements ChecksumValidatorInterface
{
    public function __construct(
        private readonly ChecksumBuilderInterface $hashBuilder,
        private readonly KeyVaultInterface        $vault,
    ) {}

    public function __invoke(
        string $strategy,
        string $size,
        string $imageId,
        string $format,
        string $checksum,
    ): bool {
        foreach ($this->vault as $key) {
            $hash = ($this->hashBuilder)($key, $strategy, $size, $imageId, $format);

            if (\hash_equals($hash, $checksum)) {
                return true;
            }
        }

        return false;
    }
}
