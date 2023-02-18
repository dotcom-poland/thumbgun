<?php

declare(strict_types=1);

namespace Test\App\Core\Security;

use App\Core\Security\Sha1ChecksumBuilder;
use App\Core\Security\HmacChecksumValidator;
use App\Core\Security\StringKeyVault;
use PHPUnit\Framework\TestCase;
use Test\App\Core\ContextMock;

final class HmacChecksumValidatorTest extends TestCase
{
    private readonly HmacChecksumValidator $checksumValidator;

    protected function setUp(): void
    {
        $this->checksumValidator = new HmacChecksumValidator(
            new Sha1ChecksumBuilder(),
            new StringKeyVault('key1,key2'),
        );
    }

    public function testItSupportsManyKeys(): void
    {
        $expectedChecksumForKey2 = 'sas46pon45k';

        self::assertTrue(($this->checksumValidator)(new ContextMock(
            $expectedChecksumForKey2,
            'fixed',
            '50x50',
            'webp',
            '2030004030'
        )));
    }

    public function testItReturnsFalseOnInvalidChecksum(): void
    {
        self::assertFalse(($this->checksumValidator)(new ContextMock(
            'checksum',
            'fixed',
            '50x50',
            'webp',
            '2030004030',
        )));
    }
}
