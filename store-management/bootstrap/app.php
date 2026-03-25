<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use League\Config\Exception\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        

           
         $middleware->alias([
 
            'validate.user'=>\App\Http\Middleware\ValidateUser::class,
            'validate.update.user'=>\App\Http\Middleware\ValidateUpdateUser::class,
            'auth.user'=> \App\Http\Middleware\AuthenticationMiddleware::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'validate.transfer.issue'=>\App\Http\Middleware\ValidateTransferIssue::class,
            
             




             
        ]);


    })
    ->withExceptions(function (Exceptions $exceptions) {
        
        $exceptions->render(function (NotFoundHttpException $e, $request) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Route not found'
            ], 404);
        });

        $exceptions->render(function (ValidationException $e, $request) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 422);
        });
     
        $exceptions->render(function (Throwable $e, $request) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTrace(), // optional, full stack trace
            ], 500);
        });






    })->create();
