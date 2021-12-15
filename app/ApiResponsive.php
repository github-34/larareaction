<?php


namespace App\Traits;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

trait ApiResponsive
{
    /**
     * Build a success response
     *
     * @param string|Array|Object $data
     * @param int $code
     * @return JsonResponse
     */
    public function successResponse($data, int $code = Response::HTTP_OK) : JsonResponse {

        return response()->json([ 'code' => $code, 'data' => $data ]);
    }

    /**
     * Build an error response
     *
     * @param string|Array|Object $message
     * @param int $code
     * @return JsonResponse
     */
    public function errorResponse($message, int $code) : JsonResponse {
        return response()->json(['error' => $message, 'code' => $code ]);
    }

    public function errorViewResponse($code) {
        return response()->view('errors.404');
    }
}

?>
