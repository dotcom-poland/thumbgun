<?php

declare(strict_types=1);

namespace Test\App\Core\ResizeStrategy;

use App\Core\ResizeStrategy\DefaultResizeStrategyFactory;
use App\Core\ResizeStrategy\Exception\ResizeStrategyMissingException;
use App\Core\ResizeStrategy\ResizeStrategyFixed;
use PHPUnit\Framework\TestCase;
use Test\App\Core\Filesystem\TestFilesystem;

final class DefaultResizeStrategyFactoryTest extends TestCase
{
    private readonly DefaultResizeStrategyFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new DefaultResizeStrategyFactory(
            new ResizeStrategyFixed(new TestFilesystem()),
        );
    }

    public function testReturnsResizeStrategy(): void
    {
        $strategy = ($this->factory)('fixed');

        self::assertInstanceOf(ResizeStrategyFixed::class, $strategy);
    }

    public function testThrowsExceptionOnUnsupportedStrategy(): void
    {
        $this->expectException(ResizeStrategyMissingException::class);

        ($this->factory)('invalid');
    }
}
