<?php

declare(strict_types=1);

namespace Test\App\Core\Source;

use App\Core\Image\ImageInterface;
use App\Core\Source\ImmutableSourceFactory;
use App\Core\Source\SourceInterface;
use PHPUnit\Framework\TestCase;

final class ImmutableSourceFactoryTest extends TestCase
{
    public function testItReturnsSource(): void
    {
        $source = new class implements SourceInterface {
            public function __invoke(string $imageGroup, string $imageId, string $imageFormat): ImageInterface
            {
                throw new \RuntimeException();
            }
        };

        $factory = new ImmutableSourceFactory($source);

        self::assertSame($source, $factory());
    }
}
