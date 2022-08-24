<?php

declare(strict_types=1);

namespace App\Core\ResizeStrategy;

use App\Core\Image\ImageInterface;
use App\Core\ResizeStrategy\Exception\ResizeException;

interface ResizeStrategyInterface
{
    /**
     * @throws ResizeException
     */
    public function __invoke(ImageInterface $image, SizeInterface $size): \SplFileInfo;
}
