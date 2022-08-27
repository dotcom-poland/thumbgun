<?php

declare(strict_types=1);

namespace Test\App\Core\Image;

use App\Core\Image\Exception\ImageException;
use App\Core\Image\ImmutableImage;
use PHPUnit\Framework\TestCase;

final class ImmutableImageTest extends TestCase
{
    public function testItDoesNotAllowEmptyId(): void
    {
        $this->expectException(ImageException::class);

        new ImmutableImage('', 'jpeg', new \SplTempFileObject());
    }

    public function testItDoesNotAllowEmptyRequestFormat(): void
    {
        $this->expectException(ImageException::class);

        new ImmutableImage('123', '', new \SplTempFileObject());
    }

    /** @dataProvider invalidImageIdProvider */
    public function testProhibitedSymbolsInIdAreNotAllowed(string $imageId): void
    {
        $this->expectException(ImageException::class);

        new ImmutableImage($imageId, 'jpeg', new \SplTempFileObject());
    }

    public function invalidImageIdProvider(): iterable
    {
        foreach (ImmutableImage::IMAGE_ID_PROHIBITED_SYMBOLS as $prohibitedSymbol) {
            yield ["123{$prohibitedSymbol}ABCdef.jpg"];
        }
    }
}
