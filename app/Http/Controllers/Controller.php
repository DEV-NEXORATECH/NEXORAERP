<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

abstract class Controller
{
    use ApiResponse;

    protected function isApi(): bool
    {
        return request()->is('api/*') || request()->expectsJson();
    }

    protected function apiCollection(LengthAwarePaginator|Collection $data, string $message = 'OK'): JsonResponse
    {
        return $this->respond($data, $message);
    }

    protected function apiItem(Model $data, string $message = 'OK'): JsonResponse
    {
        return $this->respond($data, $message);
    }
}
