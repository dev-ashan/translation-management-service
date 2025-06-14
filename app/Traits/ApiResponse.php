<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponse
{
    /**
     * Return a success response.
     *
     * @param mixed $data
     * @param string $message
     * @param string $resource
     * @param int $code
     * @return JsonResponse
     */
    protected function successResponse($data, string $message, string $resource = null, int $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $resource ? __("api.success.{$message}", ['resource' => __("api.resources.{$resource}")]) : __("api.success.{$message}"),
            'data' => $data
        ];

        if ($data instanceof ResourceCollection && $data->resource instanceof LengthAwarePaginator) {
            $response['meta'] = [
                'current_page' => $data->resource->currentPage(),
                'last_page' => $data->resource->lastPage(),
                'per_page' => $data->resource->perPage(),
                'total' => $data->resource->total(),
            ];
        }

        return response()->json($response, $code);
    }

    /**
     * Return an error response.
     *
     * @param string $message
     * @param string $resource
     * @param int $code
     * @param array $errors
     * @return JsonResponse
     */
    protected function errorResponse(string $message, string $resource = null, int $code = 400, array $errors = []): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $resource ? __("api.error.{$message}", ['resource' => __("api.resources.{$resource}")]) : __("api.error.{$message}"),
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Return a not found response.
     *
     * @param string $resource
     * @return JsonResponse
     */
    protected function notFoundResponse(string $resource): JsonResponse
    {
        return $this->errorResponse('not_found', $resource, 404);
    }

    /**
     * Return a validation error response.
     *
     * @param array $errors
     * @return JsonResponse
     */
    protected function validationErrorResponse(array $errors): JsonResponse
    {
        return $this->errorResponse('validation_failed', null, 422, $errors);
    }

    /**
     * Return an unauthorized response.
     *
     * @return JsonResponse
     */
    protected function unauthorizedResponse(): JsonResponse
    {
        return $this->errorResponse('unauthorized', null, 401);
    }

    /**
     * Return a forbidden response.
     *
     * @return JsonResponse
     */
    protected function forbiddenResponse(): JsonResponse
    {
        return $this->errorResponse('forbidden', null, 403);
    }

    /**
     * Return a server error response.
     *
     * @return JsonResponse
     */
    protected function serverErrorResponse(): JsonResponse
    {
        return $this->errorResponse('server_error', null, 500);
    }
} 