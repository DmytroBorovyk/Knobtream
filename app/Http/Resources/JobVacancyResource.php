<?php

namespace App\Http\Resources;

use App\Models\JobVacancy;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      @OA\Property(property="id", type="string", example="1"),
 *      @OA\Property(property="title", type="string", example="Joe Dow"),
 *      @OA\Property(property="description", type="string", example="qwerty@gmail.com"),
 *      @OA\Property(property="owner", description="owner or nothing", ref="#/components/schemas/UserResource"),
 *      @OA\Property(property="responses", description="responses or nothing", type="array",
 *          @OA\Items(
 *              ref="#/components/schemas/JobVacancyResponseResource",
 *          )
 *     ),
 *     @OA\Property(property="likes", type="integer", example=5),
 *     @OA\Property(property="tags", description="tags or nothing", type="array",
 *          @OA\Items(
 *              ref="#/components/schemas/TagResource",
 *          )
 *     ),
 *  )
 *
 * @property JobVacancy $resource
 */
class JobVacancyResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getKey(),
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'response_count' => $this->resource->response_count,
            'owner' => new UserResource($this->whenLoaded('owner')),
            'responses' => JobVacancyResponseResource::collection($this->whenLoaded('responses')),
            'likes' => $this->whenLoaded('likes', function () {
                return $this->likes->count();
            }),
            'created_at' => $this->created_at,
            'tags' => TagResource::collection($this->whenLoaded('tags')),
        ];
    }
}
