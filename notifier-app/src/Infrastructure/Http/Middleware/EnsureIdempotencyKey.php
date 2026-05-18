<?php

namespace Src\Infrastructure\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class EnsureIdempotencyKey
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $key = $request->header('X-Idempotency-Key');

        if (!$key) {
            return new JsonResponse([
                'message' => 'X-Idempotency-Key header is required.',
                'errors' => [
                    'X-Idempotency-Key' => [
                        'The X-Idempotency-Key header is required.',
                    ],
                ],
            ], 422);
        }

        if (!is_string($key) || !str($key)->isUuid()) {
            return new JsonResponse([
                'message' => 'X-Idempotency-Key must be valid UUID.',
                'errors' => [
                    'X-Idempotency-Key' => [
                        'The X-Idempotency-Key header must be a valid UUID.',
                    ],
                ],
            ], 422);
        }

        return $next($request);
    }
}
