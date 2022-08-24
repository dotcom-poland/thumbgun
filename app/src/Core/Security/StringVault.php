<?php

declare(strict_types=1);

namespace App\Core\Security;

use Traversable;

/** {@inheritDoc} */
final class StringVault implements VaultInterface
{
    private readonly string $keys;

    public function __construct(string $keys)
    {
        $this->keys = $keys;
    }

    public function getIterator(): Traversable
    {
        return new \ArrayIterator(\explode(',', $this->keys));
    }
}
