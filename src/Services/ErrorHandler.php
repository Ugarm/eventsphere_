<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

class ErrorHandler
{
    public static function handleException(Throwable $throwable): JsonResponse
    {
        return new JsonResponse([
            'code' => $throwable->getCode(),
            'message' => $throwable->getMessage()
        ]);
    }
}