<?php

declare(strict_types=1);

namespace App\Core\ResizeStrategy;

use App\Core\ResizeStrategy\Exception\SizeException;

final class SizeRectangle implements SizeInterface
{
    /** @var positive-int */
    private readonly int $width;

    /** @var positive-int */
    private readonly int $height;

    /** @throws SizeException */
    private function __construct(int $width, int $height)
    {
        if ($width <= 0 || $height <= 0) {
            throw new SizeException();
        }

        $this->width = $width;
        $this->height = $height;
    }

    /** @throws SizeException */
    public static function fromString(string $size): self
    {
        $parts = \explode('x', $size);

        if (2 !== \count($parts) || !ctype_digit($parts[0]) || !ctype_digit($parts[1])) {
            throw new SizeException();
        }

        $width = (int) $parts[0];
        $height = (int) $parts[1];

        if ($width <= 0 || $height <= 0) {
            throw new SizeException();
        }

        return new self($width, $height);
    }

    /** @return positive-int */
    public function getWidth(): int
    {
        return $this->width;
    }

    /** @return positive-int */
    public function getHeight(): int
    {
        return $this->height;
    }
}
