<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($request->is('api/*')) {
            if ($e instanceof MethodNotAllowedHttpException) {
                return response()->json([
                    'message' => 'O método HTTP não é permitido para esta rota.'
                ], 405);
            }

            if ($e instanceof NotFoundHttpException) {
                return response()->json([
                    'message' => 'URL não encontrada.'
                ], 404);
            }

            if ($e instanceof ModelNotFoundException) {
                return response()->json([
                    'message' => 'Recurso não encontrado.'
                ], 404);
            }

            if ($e instanceof ValidationException) {
                return response()->json([
                    'message' => 'Erro de validação nos dados fornecidos.',
                    'errors' => $e->errors()
                ], 422);
            }

            if ($e instanceof AuthorizationException) {
                return response()->json([
                    'message' => 'Você não tem permissão para realizar esta ação.'
                ], 403);
            }

            if ($e instanceof AuthenticationException) {
                return response()->json([
                    'message' => 'Não autenticado.'
                ], 401);
            }

            if ($e instanceof HttpException) {
                return response()->json([
                    'message' => $e->getMessage()
                ], $e->getStatusCode());
            }
        }

        return parent::render($request, $e);
    }
}
