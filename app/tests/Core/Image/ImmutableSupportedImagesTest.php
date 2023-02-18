<?php

declare(strict_types=1);

namespace Test\App\Core\Image;

use App\Core\Image\ImmutableSupportedImages;
use PHPUnit\Framework\TestCase;
use Test\App\Core\ContextMock;

final class ImmutableSupportedImagesTest extends TestCase
{
    public function testSupportedFormat(): void
    {
        $sut = new ImmutableSupportedImages(['jpeg', 'gif']);

        self::assertTrue($sut->isSupported(new ContextMock(imageFormat: 'jpeg')));
    }

    public function testUnsupportedFormat(): void
    {
        $sut = new ImmutableSupportedImages(['png', 'jpeg']);

        self::assertFalse($sut->isSupported(new ContextMock(imageFormat: 'bmp')));
    }
}
