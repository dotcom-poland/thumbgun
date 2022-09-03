<?php

declare(strict_types=1);

namespace Test\App\Core\ResizeStrategy;

use App\Core\ResizeStrategy\Exception\SizeException;
use App\Core\ResizeStrategy\SizeRectangle;
use PHPUnit\Framework\TestCase;

final class SizeRectangleTest extends TestCase
{
    public function testItCreatesFromString(): void
    {
        $this->expectNotToPerformAssertions();

        SizeRectangle::fromString('100x75');
    }

    /** @dataProvider invalidFormats */
    public function testItDoesNotAllowInvalidFormat(string $input): void
    {
        $this->expectException(SizeException::class);

        SizeRectangle::fromString($input);
    }

    public function invalidFormats(): iterable
    {
        yield 'empty input' => [''];
        yield 'zero values' => ['x'];
        yield 'invalid format' => ['300'];
        yield 'zero width' => ['0x300'];
        yield 'zero height' => ['300x0'];
        yield 'negative width' => ['300x-1'];
        yield 'negative height' => ['-1x300'];
        yield 'non number width' => ['ax300'];
        yield 'non number height' => ['300xa'];
        yield 'malformed width' => ['1.1x300'];
        yield 'malformed height' => ['300x1.1'];
        yield 'width starts with zero' => ['020x300'];
        yield 'height starts with zero' => ['200x030'];
    }

    public function testToString(): void
    {
        $size = SizeRectangle::fromString('110x80');

        self::assertEquals('110x80', $size->toString());
    }
}
