<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (ValidationException $e, $request) {
            if ($request->is('api/*'))
                return response()->json(['status' => 'error', 'code' => Response::HTTP_UNPROCESSABLE_ENTITY, 'message' => $e->getMessage(), 'errors' => $e->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        });

        $this->renderable(function (HttpException $e, $request) {
            if ($request->is('api/*'))
                return response()->json(['status' => 'error', 'code' => $e->getStatusCode(), 'message' => $e->getMessage()], $e->getStatusCode());
        });

        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->is('api/*'))
                return response()->json(['status' => 'error', 'code' => Response::HTTP_UNAUTHORIZED, 'message' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        });

        $this->renderable(function (AuthorizationException $e, $request) {
            if ($request->is('api/*'))
                return response()->json(['status' => 'error', 'code' => Response::HTTP_FORBIDDEN, 'message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        });
        $this->renderable(function (Throwable $e, $request) {
            // if ($request->is('api/*'))
            //     return response()->json(['status' => 'error', 'code' => Response::HTTP_INTERNAL_SERVER_ERROR, 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        });

        $this->reportable(function (Throwable $e) {
            Log::debug($e->getMessage());
            // if (request()->is('api/*')) {
            //     return response()->json(['status' => 'error', 'code' => Response::HTTP_INTERNAL_SERVER_ERROR, 'message' => ''], Response::HTTP_INTERNAL_SERVER_ERROR);
            // }
        });
    }
}
