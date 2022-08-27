<?php

declare(strict_types=1);

namespace App\Core\Source;

interface ImageSourceFactoryInterface
{
    public function __invoke(): ImageSourceInterface;
}
