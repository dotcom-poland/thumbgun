<?php

declare(strict_types=1);

namespace Test\App\Core\Security;

use App\Core\Security\ImmutableKey;
use App\Core\Security\StringKeyVault;
use PHPUnit\Framework\TestCase;

final class StringVaultTest extends TestCase
{
    public function testItReturnsKeys(): void
    {
        $vault = new StringKeyVault('123,345,test,key');

        $result = \iterator_to_array($vault);

        self::assertEquals([
            new ImmutableKey('123'),
            new ImmutableKey('345'),
            new ImmutableKey('test'),
            new ImmutableKey('key'),
        ], $result);
    }
}
