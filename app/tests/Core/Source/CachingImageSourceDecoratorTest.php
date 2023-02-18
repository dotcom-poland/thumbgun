<?php

namespace Test\App\Core\Source;

use App\Core\Source\CachingImageSourceDecorator;
use PHPUnit\Framework\TestCase;
use Test\App\Core\ContextMock;

final class CachingImageSourceDecoratorTest extends TestCase
{
    private const IMAGE_PATH = '/tmp/image.jpg';

    private readonly CachingImageSourceDecorator $source;

    protected function setUp(): void
    {
        $this->source = new CachingImageSourceDecorator(new TestImageSource(), '/tmp');
    }

    protected function tearDown(): void
    {
        \unlink(self::IMAGE_PATH);
        \clearstatcache();
    }

    public function testItServesImageFromFilesystemIfFileExists(): void
    {
        \file_put_contents(self::IMAGE_PATH, 'bar');

        $image = ($this->source)(new ContextMock(imageFormat: 'webp', imageId: 'image.jpg'));

        self::assertSame('bar', $image->getSource()());
    }

    public function testItServesImageFromSourceIfFileDoesNotExist(): void
    {
        $image = ($this->source)(new ContextMock(imageFormat: 'webp', imageId: 'image.jpg'));

        self::assertStringContainsString('JFIF', $image->getSource()());
    }
}
