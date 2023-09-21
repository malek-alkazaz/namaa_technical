<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException as HttpResponseExceptionAlias;
use Illuminate\Validation\ValidationException;
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


    public function render($request, Throwable $exception)
    {
        if ($request->wantsJson()) {
            return $this->handleApiException($request, $exception);
        } else {
            $retval = parent::render($request, $exception);
        }

        return $retval;
    }

    public function handleApiException($request, $exception)
    {
        $exception = $this->prepareException($exception);

        if ($exception instanceof HttpResponseExceptionAlias) {
            $exception = $exception->getResponse();
        }

        if ($exception instanceof AuthenticationException) {
            $exception = $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof ValidationException) {
            $exception = $this->convertValidationExceptionToResponse($exception, $request);
        }

        return $this->customApiResponse($exception);
    }


    private function customApiResponse($exception)
    {
        // dd($exception);
        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = 400;
        }

        $errors = [];

        switch ($statusCode) {
            case 401:
                $errors[] = __('errors.unauthorized');
                break;
            case 403:
                $errors[] = __('errors.forbidden');
                break;
            case 404:
                $errors[] = __('errors.not_found');
                break;
            case 405:
                $errors[] = __('errors.method_not_allowed');
                break;
            case 422:
                foreach ($exception->original['errors'] as $error) {
                    $errors[] = $error;
                }
                break;
            default:
                $errors[] = $exception->getMessage();
                // $errors[] = ($statusCode == 500) ? __('errors.general_error') : $exception->getMessage();
                break;
        }

        $response['status'] = $statusCode;

        return response()->json([
            'message' => '',
            'status' => false,
            'data' => null,
            'errors' => $errors,
            'status_code' => $statusCode,
        ],$statusCode);
    }
}
