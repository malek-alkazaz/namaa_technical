<?php

namespace App\Http\Controllers\V1\Api;

use Illuminate\Http\Request;
use App\Common\SharedMessage;
use Illuminate\Http\JsonResponse;
use App\Traits\V1\Api\ApiResponse;
use App\Http\Controllers\Controller;

class BaseApiController extends Controller
{
    use ApiResponse;

    /**
     * Handle manager messages.
     *
     * @param  SharedMessage  $message
     * @return JsonResponse
     */
    protected function handleSharedMessage(SharedMessage $message): JsonResponse
    {
        // Check on message status.
        if ($message->status) {
            // Return success response.
            return $this->success(
                $message->data,
                $message->message,
                $message->statusCode ?? JsonResponse::HTTP_OK ,
                $message->paginationData
            );
        }
        // Handle error of this message.
        return $this->error(
            [$message->message],
            $message->message,
            $message->statusCode ?? JsonResponse::HTTP_BAD_REQUEST
        );
    }
}
