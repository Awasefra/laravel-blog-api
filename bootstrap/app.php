<?php

use Illuminate\Http\Response;
use App\Http\Resources\ApiResource;
use App\Http\Middleware\JsonContent;
use Illuminate\Foundation\Application;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            "forceJson" => JsonContent::class
        ]);

        $middleware->api(prepend: [
            JsonContent::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (NotFoundHttpException $e) {
            return response()->json(new ApiResource(false, 404, "Route not found", null), 404);
        });

        $exceptions->render(function (AuthenticationException $e) {
            return response()->json(new ApiResource(false, 401, "Access Denied : Unauthenticated", null), 401);
        });

        $exceptions->render(function (MethodNotAllowedHttpException $e) {
            return response()->json(new ApiResource(false, Response::HTTP_METHOD_NOT_ALLOWED, "Method not allowed", null), 405);
        });
    })->create();
