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
        if (! $request->inertia()) {
            return null;
        }

        if ($e instanceof NotFoundHttpException) {
            return inertia('Errors/NotFound')
                ->toResponse($request)
                ->setStatusCode(404);
        }

        if ($e instanceof HttpException) {
            return redirect()->back()->with(
                'error',
                $e->getMessage() ?: 'Ocorreu um erro inesperado.'
            );
        }

        return null;
    }
}
