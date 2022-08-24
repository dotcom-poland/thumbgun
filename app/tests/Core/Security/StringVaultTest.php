<?php

declare(strict_types=1);

namespace Test\App\Core\Security;

use App\Core\Security\StringVault;
use PHPUnit\Framework\TestCase;

final class StringVaultTest extends TestCase
{
    public function testItReturnsKeys(): void
    {
        $vault = new StringVault('123,345,test,key');

        $result = \iterator_to_array($vault);

        self::assertSame(['123', '345', 'test', 'key'], $result);
    }
}
