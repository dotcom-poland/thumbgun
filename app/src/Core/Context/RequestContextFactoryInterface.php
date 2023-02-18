<?php

namespace App\Core\Context;

use App\Core\RequestContextInterface;

interface RequestContextFactoryInterface
{
    public function __invoke(): RequestContextInterface;
}
