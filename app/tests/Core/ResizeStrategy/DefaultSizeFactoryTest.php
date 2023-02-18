<?php

declare(strict_types=1);

namespace Test\App\Core\ResizeStrategy;

use App\Core\ResizeStrategy\DefaultSizeFactory;
use App\Core\ResizeStrategy\Exception\SizeException;
use App\Core\ResizeStrategy\SizeRectangle;
use PHPUnit\Framework\TestCase;
use Test\App\Core\ContextMock;

final class DefaultSizeFactoryTest extends TestCase
{
    public function testItReturnsRectangle(): void
    {
        $size = (new DefaultSizeFactory())(new ContextMock(sizeFormat: '200x300'));

        self::assertInstanceOf(SizeRectangle::class, $size);
        self::assertEquals(200, $size->getWidth());
        self::assertEquals(300, $size->getHeight());
    }

    public function testItThrowsExceptionOnUnsupportedFormat(): void
    {
        $this->expectException(SizeException::class);

        (new DefaultSizeFactory())(new ContextMock(sizeFormat: 'foo'));
    }
}
