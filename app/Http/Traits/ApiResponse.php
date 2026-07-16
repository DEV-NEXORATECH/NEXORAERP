<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

trait ApiResponse
{
    protected function apiOrWeb(bool $isApi, callable $apiFn, callable $webFn): mixed
    {
        return $isApi ? $apiFn() : $webFn();
    }

    protected function respond(mixed $data, string $message = 'OK', int $code = 200): JsonResponse
    {
        if ($data instanceof LengthAwarePaginator) {
            return response()->json([
                'message' => $message,
                'data' => $data->items(),
                'meta' => [
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                    'per_page' => $data->perPage(),
                    'total' => $data->total(),
                ],
            ], $code);
        }

        if ($data instanceof Collection) {
            return response()->json([
                'message' => $message,
                'data' => $data->values(),
            ], $code);
        }

        if ($data instanceof Model) {
            return response()->json([
                'message' => $message,
                'data' => $data,
            ], $code);
        }

        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function respondDeleted(string $message = 'Deleted successfully.'): JsonResponse
    {
        return response()->json(['message' => $message]);
    }

    protected function respondError(string $message, int $code = 400): JsonResponse
    {
        return response()->json(['message' => $message], $code);
    }
}
