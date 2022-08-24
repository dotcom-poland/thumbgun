<?php

declare(strict_types=1);

namespace Test\App\Core\Image;

use App\Core\Image\DefaultSizeFactory;
use App\Core\ResizeStrategy\Exception\SizeException;
use App\Core\ResizeStrategy\SizeRectangle;
use PHPUnit\Framework\TestCase;

final class DefaultSizeFactoryTest extends TestCase
{
    public function testItReturnsRectangle(): void
    {
        $size = (new DefaultSizeFactory())('200x300');

        self::assertInstanceOf(SizeRectangle::class, $size);
        self::assertEquals(200, $size->getWidth());
        self::assertEquals(300, $size->getHeight());
    }

    public function testItThrowsExceptionOnUnsupportedFormat(): void
    {
        $this->expectException(SizeException::class);

        (new DefaultSizeFactory())('foo');
    }
}
