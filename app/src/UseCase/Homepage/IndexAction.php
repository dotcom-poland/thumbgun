<?php

declare(strict_types=1);

namespace App\UseCase\Homepage;

use Symfony\Component\HttpFoundation\JsonResponse;

final class IndexAction
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse(['time' => (new \DateTime())->format(DATE_ATOM)]);
    }
}
