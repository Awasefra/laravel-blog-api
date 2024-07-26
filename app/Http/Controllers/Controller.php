<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

abstract class Controller
{
    protected function successResponse(mixed $data = null, string|null $message = "", int $code = 200): JsonResponse
    {
        // Validte the code.
        // Success response has code in range of 200 domain.
        if ($code < 200 && $code > 299) {
            return $this->errorResponse(null, "Invalid status code specified", 500);
        }

        return $this->generateResponse($data, $message, $code, true);
    }

    protected function errorResponse(mixed $data = null, string|null $message = "", int|string $code = 400): JsonResponse
    {
        // Generate default code if the code specified on args are not valid code.
        // By default it is backend server fault.
        // So throw 500 error code if there was no valid error code.
        if (!array_key_exists($code, Response::$statusTexts)) {
            $code = 500;
        }

        return $this->generateResponse($data, $message, $code, false);
    }

    private function generateResponse(mixed $data, string|null $message = "", int $code, bool $success)
    {
        // Generate default message if message is specified null
        // Default message are from default HTTPCode string.
        if (!$message) {
            $message = Response::$statusTexts[$code];
        }

        return response()->json(new ApiResource($success, $code, $message, $data), $code);
    }
}
