<?php

namespace Test\App\Core\Processor;

use App\Core\Image\ImageInterface;
use App\Core\Image\ImmutableImage;
use App\Core\Processor\CachingThumbnailProcessorDecorator;
use App\Core\Processor\DefaultThumbnailProcessor;
use App\Core\ResizeStrategy\ResizeStrategyInterface;
use App\Core\ResizeStrategy\SizeInterface;
use App\Core\ResizeStrategy\SizeRectangle;
use PHPUnit\Framework\TestCase;

final class CachingThumbnailProcessorDecoratorTest extends TestCase
{
    private const TEMP_PATH = '/tmp';
    private const IMAGE_PATH = self::TEMP_PATH . '/funky/300x275/webp/image.jpg';

    private readonly CachingThumbnailProcessorDecorator $processor;

    protected function setUp(): void
    {
        $this->processor = new CachingThumbnailProcessorDecorator(
            new DefaultThumbnailProcessor(),
            self::TEMP_PATH,
        );
    }

    protected function tearDown(): void
    {
        \unlink(self::IMAGE_PATH);

        $path = \dirname(self::IMAGE_PATH);
        while ($path !== self::TEMP_PATH) {
            \rmdir($path);
            $path = \dirname($path);
        }

        \clearstatcache();
    }

    public function testItServesImageFromFilesystemIfFileExists(): void
    {
        \mkdir(\dirname(self::IMAGE_PATH), 0777, true);
        \file_put_contents(self::IMAGE_PATH, 'existing-jpeg-content');

        $result = ($this->processor)(
            new ImmutableImage('image.jpg', 'webp', static fn(): string => 'new-jpeg-content'),
            $this->createStrategy(),
            SizeRectangle::fromString('300x275'),
        );

        self::assertEquals('existing-jpeg-content', $result);
    }

    public function testItServesImageFromSourceIfFileDoesNotExist(): void
    {
        $result = ($this->processor)(
            new ImmutableImage('image.jpg', 'webp', static fn(): string => 'new-jpeg-content'),
            $this->createStrategy(),
            SizeRectangle::fromString('300x275'),
        );

        self::assertEquals('new-jpeg-content', $result);
    }

    private function createStrategy(): ResizeStrategyInterface
    {
        return new class implements ResizeStrategyInterface {
            public function resize(ImageInterface $image, SizeInterface $size): string
            {
                return $image->getSource()();
            }

            public function toString(): string
            {
                return 'funky';
            }
        };
    }
}
