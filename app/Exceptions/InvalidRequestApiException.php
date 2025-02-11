<?php

namespace App\Exceptions;

use App\Http\Resources\ErrorResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class InvalidRequestApiException extends Exception
{
    /**
     * Report the exception.
     */
    public function report(): void
    {
        Log::error('Error en el sistema', [
            'exception' => $this,
        ]);
    }

    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request): \Illuminate\Http\JsonResponse
    {
        return (new ErrorResource($this))
            ->response()
            ->setStatusCode($this->code);
    }
}
