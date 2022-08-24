<?php

declare(strict_types=1);

namespace App\Core\Source;

interface SourceFactoryInterface
{
    public function __invoke(): SourceInterface;
}
