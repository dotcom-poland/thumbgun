<?php

declare(strict_types=1);

namespace Test\App\Core\Source;

use App\Core\Image\ImageInterface;
use App\Core\Source\ImmutableImageSourceFactory;
use App\Core\Source\ImageSourceInterface;
use PHPUnit\Framework\TestCase;

final class ImmutableImageSourceFactoryTest extends TestCase
{
    public function testItReturnsSource(): void
    {
        $source = new class implements ImageSourceInterface {
            public function __invoke(string $imageId, string $imageFormat): ImageInterface
            {
                throw new \RuntimeException();
            }
        };

        $factory = new ImmutableImageSourceFactory($source);

        self::assertSame($source, $factory());
    }
}
