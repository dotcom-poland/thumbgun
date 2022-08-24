<?php

declare(strict_types=1);

namespace Test\App\Core\Image;

use App\Core\Image\Exception\ImageException;
use App\Core\Image\ImmutableImage;
use PHPUnit\Framework\TestCase;

final class ImmutableImageTest extends TestCase
{
    public function testItDoesNotAllowEmptyGroup(): void
    {
        $this->expectException(ImageException::class);

        new ImmutableImage('', '123', 'jpeg', new \SplTempFileObject());
    }

    public function testItDoesNotAllowEmptyId(): void
    {
        $this->expectException(ImageException::class);

        new ImmutableImage('group', '', 'jpeg', new \SplTempFileObject());
    }

    /** @dataProvider invalidImageIdProvider */
    public function testItOnlyAllowsCertainCharacterSetsAsTheImageIdToPreventHacking(string $imageId): void
    {
        $this->expectException(ImageException::class);

        new ImmutableImage('group', $imageId, 'jpeg', new \SplTempFileObject());
    }

    public function invalidImageIdProvider(): iterable
    {
        $prohibitedSymbols = [
            '~', '`', '!', '@', '#', '$', '%', '^', '&', '*', '*', '(', ')',
            '+', '=', '[', ']', '{', '}', ':', ';', '<', '>', ',', '?', '/', '\\',
            '|',
        ];

        foreach ($prohibitedSymbols as $symbol) {
            yield ["123{$symbol}ABCdef.jpg"];
        }
    }

    public function testItDoesNotAllowEmptyRequestFormat(): void
    {
        $this->expectException(ImageException::class);

        new ImmutableImage('group', '123', '', new \SplTempFileObject());
    }
}
