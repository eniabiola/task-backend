<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Traits\HasResponseTrait;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,

        ]);
    })->withExceptions(function (Exceptions $exceptions) {
        $responder = new class {use HasResponseTrait;};
        $exceptions->renderable(function (Throwable $e, Request $request) use ($responder) {
            {
                logger($e->getMessage());
            }
            if (!$request->expectsJson()) {
                return null;
            }

            if ($e instanceof ModelNotFoundException || $e->getPrevious() instanceof ModelNotFoundException) {
                $modelException = $e instanceof ModelNotFoundException ? $e : $e->getPrevious();
                $fullClass = $modelException->getModel();
                $model = class_basename($fullClass);

                return $responder->failedResponse("{$model} not found.");
            }

            if ($e instanceof NotFoundHttpException) {
                return $responder->failedResponse("Invalid Request.");
            }
            if ($e instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
                return $responder->failedResponse("Unauthorized.", [], 403);
            }

          /*  return response()->json([
                'message' => 'An unexpected error occurred.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);*/
        });
    })->create();
