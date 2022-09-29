<?php

namespace App\Http\Resources;

use App\Models\JobVacancyResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      @OA\Property(property="id", type="string", example="1"),
 *      @OA\Property(property="review_text", type="string", example="review text"),
 *      @OA\Property(property="owner", description="owner or nothing", ref="#/components/schemas/UserResource"),
 *      @OA\Property(property="vacancy", description="vacancy or nothing", ref="#/components/schemas/JobVacancyResource"),
 *  )
 *
 * @property JobVacancyResponse $resource
 */
class JobVacancyResponseResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getKey(),
            'review_text' => $this->resource->review_text,
            'owner' => new UserResource($this->whenLoaded('owner')),
            'vacancy' => new JobVacancyResource($this->whenLoaded('vacancy')),
        ];
    }
}
