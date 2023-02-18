<?php

declare(strict_types=1);

namespace App\Core\ResizeStrategy;

use App\Core\RequestContextInterface;
use App\Core\ResizeStrategy\Exception\SizeException;

/** @psalm-pure */
interface SizeFactoryInterface
{
    /** @throws SizeException */
    public function __invoke(RequestContextInterface $context): SizeInterface;
}
