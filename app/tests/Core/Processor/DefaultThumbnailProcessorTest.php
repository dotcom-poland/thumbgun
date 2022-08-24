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
        $file = (new \SplTempFileObject())->getFileInfo();
        $image = new ImmutableImage('group', 'id', 'jpg', $file);
        $size = SizeRectangle::fromString('300x275');
        $strategy = new class($file) implements ResizeStrategyInterface {
            public function __construct(
                private readonly \SplFileInfo $file,
            ) {}

            public function __invoke(ImageInterface $image, SizeInterface $size): \SplFileInfo
            {
                return $this->file;
            }
        };

        $result = (new DefaultThumbnailProcessor())($image, $strategy, $size);

        self::assertSame($file, $result);
    }
}
