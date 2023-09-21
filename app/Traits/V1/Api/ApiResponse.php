<?php


namespace App\Traits\V1\Api;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Core of response
     *
     * @param string $message
     * @param mixed $data
     * @param int $statusCode
     * @param array $errors
     * @param bool $isSuccess
     * @param null|mixed $paginationData
     * @return JsonResponse
     */
    private function coreResponse(string $message, $data, int $statusCode, array $errors, bool $isSuccess = true , $paginationData = null): JsonResponse
    {
        // Check the params
        if (! $message && ! $errors) {
            return response()->json(['message' => 'Message is required'], 500);
        }

        // Send the response
        if ($isSuccess) {
            return response()->json([
                'message' => $message,
                'status' => true,
                'data' => $data,
                'status_code' => $statusCode,
                'pagination_data' => $paginationData ,
            ],$statusCode);
        }

        return response()->json([
            'message' => $message,
            'status' => false,
            'data' => null,
            'errors' => $errors,
            'status_code' => $statusCode,
            'pagination_data' => $paginationData ,
        ], $statusCode);
    }

    /**
     * Send any success response
     *
     * @param  string  $message
     * @param  array|object  $data
     * @param  int  $statusCode
     * @return JsonResponse
     */
    public function success($data, string $message = null, int $statusCode = 200 , $paginationData = null): JsonResponse
    {
        $message = $message ? $message : __('success.success');

        return $this->coreResponse($message, $data, $statusCode, [], true , $paginationData);
    }

    /**
     * Send any error response
     *
     * @param  array  $messages
     * @param  int  $statusCode
     * @return JsonResponse
     */
    public function error(array $messages, string $message = null, int $statusCode = 500): JsonResponse
    {
        $message = $message ? $message : __('errors.error');

        return $this->coreResponse($message, null, $statusCode, $messages, false);
    }

}
