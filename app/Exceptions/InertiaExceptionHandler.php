<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InertiaExceptionHandler
{
    public function __invoke(Throwable $e, Request $request)
    {
        // Só trata erros vindos do Inertia
        if (! $request->inertia()) {
            return null;
        }

        // 404 - Página não encontrada
        if ($e instanceof NotFoundHttpException) {
            return inertia('Errors/NotFound')
                ->toResponse($request)
                ->setStatusCode(404);
        }

        // 403 - Proibido
        if ($e instanceof HttpException && $e->getStatusCode() === 403) {
            return inertia('Errors/Forbidden')
                ->toResponse($request)
                ->setStatusCode(403);
        }

        // 500 ou outros erros genéricos
        if ($e instanceof HttpException) {
            return redirect()->back()->with(
                'error',
                $e->getMessage() ?: 'Ocorreu um erro inesperado.'
            );
        }

        return null; // Deixa o Laravel tratar o restante
    }
}
