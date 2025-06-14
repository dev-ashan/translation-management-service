<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Tag\TagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use App\Services\TagService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Tags",
 *     description="API Endpoints for managing tags"
 * )
 */
class TagController extends Controller
{
    use ApiResponse;

    protected TagService $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    /**
     * @OA\Get(
     *     path="/tags",
     *     summary="List tags",
     *     tags={"Tags"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of tags",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Tags retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="frontend"),
     *                     @OA\Property(property="description", type="string", example="Frontend translations"),
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
        $tags = $this->tagService->getAllTags($request->all());
        return $this->successResponse(
            TagResource::collection($tags),
            'Tags retrieved successfully'
        );
    }

    /**
     * @OA\Post(
     *     path="/tags",
     *     summary="Create a new tag",
     *     tags={"Tags"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="frontend"),
     *             @OA\Property(property="description", type="string", example="Frontend translations")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tag created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Tag created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="frontend"),
     *                 @OA\Property(property="description", type="string", example="Frontend translations"),
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
     *                 @OA\Property(property="name", type="array", @OA\Items(type="string"))
     *             )
     *         )
     *     )
     * )
     */
    public function store(TagRequest $request): JsonResponse
    {
        $tag = $this->tagService->createTag($request->validated());
        return $this->createdResponse(
            new TagResource($tag),
            'Tag created successfully'
        );
    }

    /**
     * @OA\Get(
     *     path="/tags/{id}",
     *     summary="Get a specific tag",
     *     tags={"Tags"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Tag ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tag details",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Tag retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="frontend"),
     *                 @OA\Property(property="description", type="string", example="Frontend translations"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tag not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Tag not found")
     *         )
     *     )
     * )
     */
    public function show(Tag $tag): JsonResponse
    {
        return $this->successResponse(
            new TagResource($tag),
            'Tag retrieved successfully'
        );
    }

    /**
     * @OA\Put(
     *     path="/tags/{id}",
     *     summary="Update a tag",
     *     tags={"Tags"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Tag ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="frontend"),
     *             @OA\Property(property="description", type="string", example="Frontend translations")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tag updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Tag updated successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="frontend"),
     *                 @OA\Property(property="description", type="string", example="Frontend translations"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tag not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Tag not found")
     *         )
     *     )
     * )
     */
    public function update(TagRequest $request, Tag $tag): JsonResponse
    {
        $updatedTag = $this->tagService->updateTag($tag->id, $request->validated());
        return $this->successResponse(
            new TagResource($updatedTag),
            'Tag updated successfully'
        );
    }

    /**
     * @OA\Delete(
     *     path="/tags/{id}",
     *     summary="Delete a tag",
     *     tags={"Tags"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Tag ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tag deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Tag deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tag not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Tag not found")
     *         )
     *     )
     * )
     */
    public function destroy(Tag $tag): JsonResponse
    {
        $this->tagService->deleteTag($tag->id);
        return $this->successResponse(
            null,
            'Tag deleted successfully'
        );
    }
} 