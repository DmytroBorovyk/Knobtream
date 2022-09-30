<?php

namespace App\Http\Resources;

use App\Models\JobVacancy;
use App\Models\Tag;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      @OA\Property(property="id", type="string", example="1"),
 *      @OA\Property(property="title", type="string", example="Joe Dow"),
 *  )
 *
 * @property Tag $resource
 */
class TagResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getKey(),
            'title' => $this->resource->title,
        ];
    }
}
