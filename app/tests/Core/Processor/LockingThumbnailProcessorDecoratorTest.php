<?php

namespace Test\App\Core\Processor;

use App\Core\Image\ImageInterface;
use App\Core\Image\ImmutableImage;
use App\Core\Processor\DefaultThumbnailProcessor;
use App\Core\Processor\LockingThumbnailProcessorDecorator;
use App\Core\ResizeStrategy\ResizeStrategyInterface;
use App\Core\ResizeStrategy\SizeInterface;
use App\Core\ResizeStrategy\SizeRectangle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\Store\FlockStore;

final class LockingThumbnailProcessorDecoratorTest extends TestCase
{
    private readonly LockingThumbnailProcessorDecorator $processor;

    protected function setUp(): void
    {
        $this->processor = new LockingThumbnailProcessorDecorator(
            new DefaultThumbnailProcessor(),
            new LockFactory(new FlockStore()),
        );
    }

    public function testItAllowsToProcessTheImage(): void
    {
        $image = new ImmutableImage('id', 'jpg', static fn(): string => '');
        $size = SizeRectangle::fromString('300x275');
        $strategy = new class implements ResizeStrategyInterface {
            public function resize(ImageInterface $image, SizeInterface $size): string
            {
                return 'jpeg content';
            }

            public function toString(): string
            {
                return 'foo';
            }
        };

        $thumbnail = ($this->processor)($image, $strategy, $size);

        self::assertSame('jpeg content', $thumbnail);
    }
}
