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
    public function __invoke(ImageInterface $image, SizeInterface $size): \SplFileObject
    {
        if ($this->exception) {
            throw $this->exception;
        }

        $file = new \SplTempFileObject(100);
        $file->fwrite($this->content);
        $file->fseek(0);

        return $file;
    }
}