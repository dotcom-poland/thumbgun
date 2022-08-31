<?php

declare(strict_types=1);

namespace Test\App\Core\Security;

use App\Core\Security\Sha1ChecksumBuilder;
use App\Core\Security\HmacChecksumValidator;
use App\Core\Security\StringKeyVault;
use PHPUnit\Framework\TestCase;

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

        self::assertTrue(($this->checksumValidator)(
            'fixed',
            '50x50',
            '2030004030',
            'webp',
            $expectedChecksumForKey2,
        ));
    }


    public function testItReturnsFalseOnInvalidChecksum(): void
    {
        self::assertFalse(($this->checksumValidator)(
            'fixed',
            '50x50',
            'random',
            '2030004030',
            'checksum',
        ));
    }
}
