<?php

declare(strict_types=1);

namespace App\Core\Security;

/**
 * @psalm-pure
 */
final class ImmutableKey implements KeyInterface
{
    private string $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function __toString(): string
    {
        return $this->key;
    }
}
