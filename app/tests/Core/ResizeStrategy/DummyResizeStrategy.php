<?php

declare(strict_types=1);

namespace Test\App\Core\ResizeStrategy;

use App\Core\Image\ImageInterface;
use App\Core\ResizeStrategy\ResizeStrategyInterface;
use App\Core\ResizeStrategy\SizeInterface;

final class DummyResizeStrategy implements ResizeStrategyInterface
{
    public function __construct(
        private readonly string $content,
        private ?\Throwable $exception = null,
    ) {}

    public function setThrownException(\Throwable $exception): void
    {
        $this->exception = $exception;
    }

    /** {@inheritDoc} */
    public function resize(ImageInterface $image, SizeInterface $size): string
    {
        if ($this->exception) {
            throw $this->exception;
        }

        return $this->content;
    }

    public function toString(): string
    {
        return 'dummy';
    }
}
