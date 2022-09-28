<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * @param Request $request
     * @param Throwable $e
     * @return JsonResponse|void
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {
        if ($request->wantsJson()) {   //add Accept: application/json in request
            return $this->handleApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    private function handleApiException($request, Throwable $exception)
    {
        $exception = $this->prepareException($exception);

        if ($exception instanceof HttpResponseException) {
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
        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = 500;
        }

        $response = [];

        switch ($statusCode) {
            case 401:
                $response['error'] = ['Unauthorized'];
                break;
            case 403:
                $response['error'] = ['Forbidden'];
                break;
            case 404:
                $response['error'] = ['Not Found'];
                break;
            case 405:
                $response['error'] = ['Method Not Allowed'];
                break;
            case 422:
                $response['error'] = $exception->original['message'];
                $response['error'] = $exception->original['errors'];
                break;
            default:
                $response['error'] = [($statusCode == 500) ? 'Whoops, looks like something went wrong' : $exception->getMessage()];
                break;
        }

        if (config('app.debug') && method_exists($exception, 'getTrace')) {
            $response['trace'] = $exception->getTrace();
            $response['code'] = $exception->getCode();
        }

        $response['success'] = false;
        $response['status'] = $statusCode;

        return response()->json($response, $statusCode);
    }
}
