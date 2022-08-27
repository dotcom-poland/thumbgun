<?php

declare(strict_types=1);

namespace Test\App\Core\ResizeStrategy;

use App\Core\ResizeStrategy\ImmutableResizeStrategyFactory;
use App\Core\ResizeStrategy\Exception\ResizeStrategyException;
use App\Core\ResizeStrategy\ResizeStrategyFixed;
use PHPUnit\Framework\TestCase;
use Test\App\Core\Filesystem\TestFileFactory;

final class DefaultResizeStrategyFactoryTest extends TestCase
{
    private readonly ImmutableResizeStrategyFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new ImmutableResizeStrategyFactory([
            'fixed' => new ResizeStrategyFixed(new TestFileFactory()),
        ]);
    }

    public function testReturnsResizeStrategy(): void
    {
        $strategy = ($this->factory)('fixed');

        self::assertInstanceOf(ResizeStrategyFixed::class, $strategy);
    }

    public function testThrowsExceptionOnUnsupportedStrategy(): void
    {
        $this->expectException(ResizeStrategyException::class);

        ($this->factory)('invalid');
    }
}
