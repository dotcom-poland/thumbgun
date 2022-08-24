<?php

declare(strict_types=1);

namespace App\Core\ResizeStrategy;

use App\Core\ResizeStrategy\Exception\ResizeStrategyMissingException;

interface ResizeStrategyFactoryInterface
{
    /** @throws ResizeStrategyMissingException */
    public function __invoke(string $name): ResizeStrategyInterface;
}
