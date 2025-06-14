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
    protected function successResponse($data, string $message = null, int $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data
        ];

        if ($data instanceof ResourceCollection && $data->resource instanceof LengthAwarePaginator) {
            $response['meta'] = [
                'current_page' => $data->resource->currentPage(),
                'last_page' => $data->resource->lastPage(),
                'per_page' => $data->resource->perPage(),
                'total' => $data->resource->total(),
            ];
        } elseif ($data instanceof LengthAwarePaginator) {
            $response['meta'] = [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ];
            $response['data'] = $data->items();
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
    protected function errorResponse(string $message, int $code): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $code);
    }

    /**
     * Return a not found response.
     *
     * @param string $resource
     * @return JsonResponse
     */
    protected function notFoundResponse(string $resource): JsonResponse
    {
        return $this->errorResponse('not_found', 404);
    }

    /**
     * Return a validation error response.
     *
     * @param array $errors
     * @return JsonResponse
     */
    protected function validationErrorResponse(array $errors): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'The given data was invalid',
            'errors' => $errors
        ], 422);
    }

    /**
     * Return an unauthorized response.
     *
     * @return JsonResponse
     */
    protected function unauthorizedResponse(): JsonResponse
    {
        return $this->errorResponse('unauthorized', 401);
    }

    /**
     * Return a forbidden response.
     *
     * @return JsonResponse
     */
    protected function forbiddenResponse(): JsonResponse
    {
        return $this->errorResponse('forbidden', 403);
    }

    /**
     * Return a server error response.
     *
     * @return JsonResponse
     */
    protected function serverErrorResponse(): JsonResponse
    {
        return $this->errorResponse('server_error', 500);
    }

    /**
     * Return a created response.
     *
     * @param mixed $data
     * @param string $message
     * @return JsonResponse
     */
    protected function createdResponse($data, string $message = null): JsonResponse
    {
        return $this->successResponse($data, $message, 201);
    }
} 