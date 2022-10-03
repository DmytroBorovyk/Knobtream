<?php

namespace App\Http\Controllers;

use App\Http\Requests\LikeRequest;
use App\Http\Services\LikeService;
use Illuminate\Http\JsonResponse as Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Annotations as OA;

class LikeController extends Controller
{
    public function __construct(private LikeService $service)
    {
    }

    /**
     * @OA\Schema( schema="LikeRequest",
     *      @OA\Property(property="liked_id", type="string", example="1"),
     *      @OA\Property(property="type", type="string", example="job"),
     *  ),
     * @OA\Schema( schema="LikedResponse",
     *      @OA\Property(property="status", type="boolean", example=true),
     *      @OA\Property(property="message", type="string", example="Liked"),
     *  )
     * @OA\Post(
     *      path="/api/like-toggle",
     *      operationId="LikeToggle",
     *      summary="Toggle like",
     *      tags={"Like"},
     *      security={{"apiAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/LikeRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/LikedResponse"
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized operation",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/UnauthorizedResponse"
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Server error operation",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/ServerErrorResponse"
     *          )
     *      )
     *  )
     */
    public function like(LikeRequest $request): Response
    {
        return $this->service->like($request);
    }

    /**
     * @OA\Get(
     *      path="/api/liked-jobs",
     *      operationId="LikedJobs",
     *      summary="Get user liked jobs list",
     *      tags={"Like"},
     *      security={{"apiAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/JobVacancyResource")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Server error operation",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/ServerErrorResponse"
     *          )
     *      )
     *  )
     */
    public function getLikedJobs(): AnonymousResourceCollection
    {
        return $this->service->getLikedJobs();
    }

    /**
     * @OA\Get(
     *      path="/api/liked-users",
     *      operationId="LikedUsers",
     *      summary="Get user liked users list",
     *      tags={"Like"},
     *      security={{"apiAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/UserResource")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Server error operation",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/ServerErrorResponse"
     *          )
     *      )
     *  )
     */
    public function getLikedUsers(): AnonymousResourceCollection
    {
        return $this->service->getLikedUsers();
    }
}
