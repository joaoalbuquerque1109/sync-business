<?php

namespace App\Http\Controllers\Api\V1;

use App\Support\ApiResponse;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    protected function success(mixed $data = null, string $message = 'Operação realizada com sucesso', array $meta = [])
    {
        return ApiResponse::success($data, $message, $meta);
    }

    protected function error(string $message = 'Erro de validação', array $errors = [], int $status = 422)
    {
        return ApiResponse::error($message, $errors, $status);
    }
}
