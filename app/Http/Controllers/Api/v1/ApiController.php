<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{

    const OK = 200;
    const CREATED = 201;
    const ACCEPTED = 202;
    const BAD_REQUEST = 400;
    const ERROR = 500;
    const UN_AUTHORIZED = 401;

    public function success($data): JsonResponse
    {
        return $this->decorate($data, self::OK, "Success");
    }

    /**
     * Decorates a JSON response with extra information
     *
     * @param $data
     * @param int $status
     * @param String $message
     * @return JsonResponse
     */
    public function decorate($data, int $status, string $message): JsonResponse
    {
        return response()->json([
            "status" => $status,
            "message" => $message,
            "data" => $data,
        ], $status);
    }

    public function failed($data): JsonResponse
    {
        return $this->decorate($data, self::BAD_REQUEST, "Failed");
    }

    public function error($data): JsonResponse
    {
        return $this->decorate($data, self::ERROR, "Error");
    }

    public function unauthorized($data): JsonResponse
    {
        return $this->decorate($data, self::UN_AUTHORIZED, "Error");
    }
}
