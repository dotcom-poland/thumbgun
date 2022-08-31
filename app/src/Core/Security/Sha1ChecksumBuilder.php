<?php

declare(strict_types=1);

namespace App\Core\Security;

/** {@inheritDoc} */
final class Sha1ChecksumBuilder implements ChecksumBuilderInterface
{
    private const ALGO = 'sha1';

    public function __invoke(
        KeyInterface $key,
        string $strategy,
        string $size,
        string $imageId,
        string $format,
    ): string {
        $string = \sprintf('%s:%s:%s:%s', $strategy, $size, $imageId, $format);

        /** @psalm-suppress ImpureFunctionCall */
        if (false === \in_array(self::ALGO, \hash_hmac_algos(), true)) {
            throw new \RuntimeException(\sprintf(
                'Algo %s not available, only: "%s"',
                self::ALGO,
                \implode(', ', \hash_hmac_algos()),
            ));
        }

        $hashBase16 = \hash_hmac(self::ALGO, $string, (string) $key);

        return \rtrim(\base_convert($hashBase16, 16, 32), '0');
    }
}
