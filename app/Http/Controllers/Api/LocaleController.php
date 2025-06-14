<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Locale\LocaleRequest;
use App\Http\Resources\LocaleResource;
use App\Models\Locale;
use App\Services\LocaleService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Locales",
 *     description="API Endpoints for managing locales"
 * )
 */
class LocaleController extends Controller
{
    use ApiResponse;

    protected LocaleService $localeService;

    public function __construct(LocaleService $localeService)
    {
        $this->localeService = $localeService;
    }

    /**
     * @OA\Get(
     *     path="/locales",
     *     summary="List locales",
     *     tags={"Locales"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of locales",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Locales retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="code", type="string", example="en"),
     *                     @OA\Property(property="name", type="string", example="English"),
     *                     @OA\Property(property="is_active", type="boolean", example=true),
     *                     @OA\Property(property="is_default", type="boolean", example=true),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $locales = $this->localeService->getAllLocales($request->all());
        return $this->successResponse(
            LocaleResource::collection($locales),
            'Locales retrieved successfully'
        );
    }

    /**
     * @OA\Post(
     *     path="/locales",
     *     summary="Create a new locale",
     *     tags={"Locales"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"code", "name"},
     *             @OA\Property(property="code", type="string", example="en"),
     *             @OA\Property(property="name", type="string", example="English"),
     *             @OA\Property(property="is_active", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Locale created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Locale created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="code", type="string", example="en"),
     *                 @OA\Property(property="name", type="string", example="English"),
     *                 @OA\Property(property="is_active", type="boolean", example=true),
     *                 @OA\Property(property="is_default", type="boolean", example=false),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="The given data was invalid"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(property="code", type="array", @OA\Items(type="string"))
     *             )
     *         )
     *     )
     * )
     */
    public function store(LocaleRequest $request): JsonResponse
    {
        $locale = $this->localeService->createLocale($request->validated());
        return $this->createdResponse(
            new LocaleResource($locale),
            'Locale created successfully'
        );
    }

    /**
     * @OA\Get(
     *     path="/locales/{id}",
     *     summary="Get a specific locale",
     *     tags={"Locales"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Locale ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Locale details",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Locale retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="code", type="string", example="en"),
     *                 @OA\Property(property="name", type="string", example="English"),
     *                 @OA\Property(property="is_active", type="boolean", example=true),
     *                 @OA\Property(property="is_default", type="boolean", example=true),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Locale not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Locale not found")
     *         )
     *     )
     * )
     */
    public function show(Locale $locale): JsonResponse
    {
        return $this->successResponse(
            new LocaleResource($locale),
            'Locale retrieved successfully'
        );
    }

    /**
     * @OA\Put(
     *     path="/locales/{id}",
     *     summary="Update a locale",
     *     tags={"Locales"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Locale ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"code", "name"},
     *             @OA\Property(property="code", type="string", example="en"),
     *             @OA\Property(property="name", type="string", example="English"),
     *             @OA\Property(property="is_active", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Locale updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Locale updated successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="code", type="string", example="en"),
     *                 @OA\Property(property="name", type="string", example="English"),
     *                 @OA\Property(property="is_active", type="boolean", example=true),
     *                 @OA\Property(property="is_default", type="boolean", example=true),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Locale not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Locale not found")
     *         )
     *     )
     * )
     */
    public function update(LocaleRequest $request, Locale $locale): JsonResponse
    {
        $updatedLocale = $this->localeService->updateLocale($locale->id, $request->validated());
        return $this->successResponse(
            new LocaleResource($updatedLocale),
            'Locale updated successfully'
        );
    }

    /**
     * @OA\Delete(
     *     path="/locales/{id}",
     *     summary="Delete a locale",
     *     tags={"Locales"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Locale ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Locale deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Locale deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Locale not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Locale not found")
     *         )
     *     )
     * )
     */
    public function destroy(Locale $locale): JsonResponse
    {
        $this->localeService->deleteLocale($locale->id);
        return $this->successResponse(
            null,
            'Locale deleted successfully'
        );
    }

    /**
     * @OA\Post(
     *     path="/locales/{id}/restore",
     *     summary="Restore a deleted locale",
     *     tags={"Locales"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Locale ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Locale restored successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Locale restored successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="code", type="string", example="en"),
     *                 @OA\Property(property="name", type="string", example="English"),
     *                 @OA\Property(property="is_active", type="boolean", example=true),
     *                 @OA\Property(property="is_default", type="boolean", example=true),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Locale not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Locale not found")
     *         )
     *     )
     * )
     */
    public function restore(Locale $locale): JsonResponse
    {
        $restoredLocale = $this->localeService->restoreLocale($locale->id);
        return $this->successResponse(
            new LocaleResource($restoredLocale),
            'Locale restored successfully'
        );
    }
} 