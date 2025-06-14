<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Translation\StoreTranslationRequest;
use App\Http\Requests\Api\Translation\UpdateTranslationRequest;
use App\Http\Resources\TranslationResource;
use App\Models\Translation;
use App\Services\TranslationService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Translations",
 *     description="API Endpoints for managing translations"
 * )
 */
class TranslationController extends Controller
{
    use ApiResponse;

    protected TranslationService $translationService;

    /**
     * Create a new controller instance.
     *
     * @param TranslationService $translationService The translation service instance
     */
    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
        $this->middleware('auth:sanctum');
    }

    /**
     * @OA\Get(
     *     path="/translations",
     *     summary="List translations",
     *     tags={"Translations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="locale",
     *         in="query",
     *         description="Filter by locale code",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="tags",
     *         in="query",
     *         description="Filter by tag names (comma-separated)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         description="Search in key and value",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of translations",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Translations retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="locale_id", type="integer", example=1),
     *                     @OA\Property(property="key", type="string", example="welcome"),
     *                     @OA\Property(property="value", type="string", example="Welcome to our application"),
     *                     @OA\Property(property="group", type="string", example="general"),
     *                     @OA\Property(
     *                         property="tags",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="name", type="string", example="frontend")
     *                         )
     *                     )
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=1),
     *                 @OA\Property(property="per_page", type="integer", example=15),
     *                 @OA\Property(property="total", type="integer", example=1)
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $translations = $this->translationService->getAllTranslations($request->all());
        return $this->successResponse(
            TranslationResource::collection($translations),
            'Translations retrieved successfully'
        );
    }

    /**
     * @OA\Post(
     *     path="/translations",
     *     summary="Create a new translation",
     *     tags={"Translations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"locale_id", "key", "value", "group"},
     *             @OA\Property(property="locale_id", type="integer", example=1),
     *             @OA\Property(property="key", type="string", example="welcome"),
     *             @OA\Property(property="value", type="string", example="Welcome to our application"),
     *             @OA\Property(property="group", type="string", example="general"),
     *             @OA\Property(
     *                 property="tags",
     *                 type="array",
     *                 @OA\Items(type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Translation created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Translation created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="locale_id", type="integer", example=1),
     *                 @OA\Property(property="key", type="string", example="welcome"),
     *                 @OA\Property(property="value", type="string", example="Welcome to our application"),
     *                 @OA\Property(property="group", type="string", example="general"),
     *                 @OA\Property(
     *                     property="tags",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="frontend")
     *                     )
     *                 )
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
     *                 @OA\Property(property="key", type="array", @OA\Items(type="string"))
     *             )
     *         )
     *     )
     * )
     */
    public function store(StoreTranslationRequest $request): JsonResponse
    {
        $translation = $this->translationService->createTranslation($request->validated());
        return $this->createdResponse(
            new TranslationResource($translation),
            'Translation created successfully'
        );
    }

    /**
     * @OA\Get(
     *     path="/translations/{id}",
     *     summary="Get a specific translation",
     *     tags={"Translations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Translation ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Translation details",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Translation retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="locale_id", type="integer", example=1),
     *                 @OA\Property(property="key", type="string", example="welcome"),
     *                 @OA\Property(property="value", type="string", example="Welcome to our application"),
     *                 @OA\Property(property="group", type="string", example="general"),
     *                 @OA\Property(
     *                     property="tags",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="frontend")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Translation not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Translation not found")
     *         )
     *     )
     * )
     */
    public function show(Translation $translation): JsonResponse
    {
        return $this->successResponse(
            new TranslationResource($translation),
            'Translation retrieved successfully'
        );
    }

    /**
     * @OA\Put(
     *     path="/translations/{id}",
     *     summary="Update a translation",
     *     tags={"Translations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Translation ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"locale_id", "key", "value", "group"},
     *             @OA\Property(property="locale_id", type="integer", example=1),
     *             @OA\Property(property="key", type="string", example="welcome"),
     *             @OA\Property(property="value", type="string", example="Welcome to our application"),
     *             @OA\Property(property="group", type="string", example="general"),
     *             @OA\Property(
     *                 property="tags",
     *                 type="array",
     *                 @OA\Items(type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Translation updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Translation updated successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="locale_id", type="integer", example=1),
     *                 @OA\Property(property="key", type="string", example="welcome"),
     *                 @OA\Property(property="value", type="string", example="Welcome to our application"),
     *                 @OA\Property(property="group", type="string", example="general"),
     *                 @OA\Property(
     *                     property="tags",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="frontend")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Translation not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Translation not found")
     *         )
     *     )
     * )
     */
    public function update(UpdateTranslationRequest $request, Translation $translation): JsonResponse
    {
        $updatedTranslation = $this->translationService->update($translation->id, $request->validated());
        return $this->successResponse(
            new TranslationResource($updatedTranslation),
            'Translation updated successfully'
        );
    }

    /**
     * @OA\Delete(
     *     path="/translations/{id}",
     *     summary="Delete a translation",
     *     tags={"Translations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Translation ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Translation deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Translation deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Translation not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Translation not found")
     *         )
     *     )
     * )
     */
    public function destroy(Translation $translation): JsonResponse
    {
        $this->translationService->deleteTranslation($translation->id);
        return $this->successResponse(null, 'Translation deleted successfully');
    }

    /**
     * @OA\Get(
     *     path="/translations/search",
     *     summary="Search translations",
     *     tags={"Translations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         required=true,
     *         description="Search query",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Search results",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Search results retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="locale_id", type="integer", example=1),
     *                     @OA\Property(property="key", type="string", example="welcome"),
     *                     @OA\Property(property="value", type="string", example="Welcome to our application"),
     *                     @OA\Property(property="group", type="string", example="general"),
     *                     @OA\Property(
     *                         property="tags",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="name", type="string", example="frontend")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function search(Request $request): JsonResponse
    {
        $translations = $this->translationService->searchTranslations($request->get('q', ''));
        return $this->successResponse(
            TranslationResource::collection($translations),
            'Translations retrieved successfully'
        );
    }
} 