<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 *  @OA\Schema(
 *      @OA\Property(property="id", type="string", example="1"),
 *      @OA\Property(property="name", type="string", example="Joe Dow"),
 *      @OA\Property(property="email", type="string", example="qwerty@gmail.com"),
 *      @OA\Property(property="email_verified_at", type="string", example="2022-09-27T13:57:35.000000Z"),
 *      @OA\Property(property="remember_token", type="string", example="79KbWY8LGrFdMQNhowXfKshSUSEJKDvsFcozAW9c"),
 *  )
 *
 * @property User $resource
 */
class UserResource extends  JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getKey(),
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'email_verified_at' => $this->resource->email_verified_at,
            'remember_token' => $this->resource->remember_token,
        ];
    }
}
