<?php

declare(strict_types=1);

namespace App\Core\Security;

use Traversable;

/** {@inheritDoc} */
final class StringKeyVault implements KeyVaultInterface
{
    private readonly string $keys;

    public function __construct(string $keys)
    {
        $this->keys = $keys;
    }

    /** {@inheritDoc} */
    public function getIterator(): Traversable
    {
        foreach (\explode(',', $this->keys) as $key) {
            yield new ImmutableKey($key);
        }
    }
}
