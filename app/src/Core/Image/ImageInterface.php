<?php

declare(strict_types=1);

namespace App\Core\Image;

use Closure;

interface ImageInterface
{
    /** @return non-empty-string */
    public function getImageId(): string;

    /** @return non-empty-string */
    public function getRequestedFormat(): string;

    /** @return Closure():string */
    public function getSource(): Closure;
}
