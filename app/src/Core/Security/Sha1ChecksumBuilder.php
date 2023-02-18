<?php

declare(strict_types=1);

namespace App\Core\Security;

use App\Core\RequestContextInterface;

/** {@inheritDoc} */
final class Sha1ChecksumBuilder implements ChecksumBuilderInterface
{
    private const ALGO = 'sha1';

    public function __invoke(KeyInterface $key, RequestContextInterface $context): string
    {
        $string = \sprintf(
            '%s:%s:%s:%s',
            $context->getStrategyName(),
            $context->getSizeFormat(),
            $context->getImageId(),
            $context->getImageFormat(),
        );

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
