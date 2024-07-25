<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponserTrait{

    protected function successResponse($data, int $httpResponseCode = 200): JsonResponse
    {
        return response()->json([
            'success'    => true,
            'data'       => $data,
        ], $httpResponseCode);
    }

    protected function errorResponse(string $message, int $httpResponseCode = 400): JsonResponse 
    {
        return response()->json([
            'success'    => false,
            'message'    => $message ?? null,
            'data'       => null,
        ], $httpResponseCode);
    }
}