<?php

namespace App\Exceptions;

use Throwable;
use App\Exceptions\OutOfStockException;
use App\Exceptions\AlreadyPaidException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // 👇 Custom handling of your domain exceptions
        if ($exception instanceof OutOfStockException) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }

        if ($exception instanceof AlreadyPaidException) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 409);
        }

        // fallback → default Laravel render
        return parent::render($request, $exception);
    }
}
