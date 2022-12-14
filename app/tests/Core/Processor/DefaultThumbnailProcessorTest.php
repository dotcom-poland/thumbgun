<?php

declare(strict_types=1);

namespace Test\App\Core\Processor;

use App\Core\Image\ImageInterface;
use App\Core\Image\ImmutableImage;
use App\Core\Processor\DefaultThumbnailProcessor;
use App\Core\ResizeStrategy\SizeRectangle;
use App\Core\ResizeStrategy\ResizeStrategyInterface;
use App\Core\ResizeStrategy\SizeInterface;
use PHPUnit\Framework\TestCase;

final class DefaultThumbnailProcessorTest extends TestCase
{
    public function testItReturnsResizeResult(): void
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

        $result = (new DefaultThumbnailProcessor())($image, $strategy, $size);

        self::assertEquals('jpeg content', $result);
    }
}
