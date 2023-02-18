<?php

declare(strict_types=1);

namespace App\Core\ResizeStrategy;

use App\Core\RequestContextInterface;
use App\Core\ResizeStrategy\Exception\ResizeStrategyException;

interface ResizeStrategyFactoryInterface
{
    /** @throws ResizeStrategyException */
    public function __invoke(RequestContextInterface $context): ResizeStrategyInterface;
}
