<?php

declare(strict_types=1);

namespace App\Core\Image;

use App\Core\ResizeStrategy\Exception\SizeException;
use App\Core\ResizeStrategy\SizeRectangle;
use App\Core\ResizeStrategy\SizeFactoryInterface;
use App\Core\ResizeStrategy\SizeInterface;

/** {@inheritDoc} */
final class DefaultSizeFactory implements SizeFactoryInterface
{
    /** {@inheritDoc} */
    public function __invoke(string $size): SizeInterface
    {
        if (\preg_match('/^\d+x\d+$/', $size)) {
            return SizeRectangle::fromString($size);
        }

        throw new SizeException();
    }
}
