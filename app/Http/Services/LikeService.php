<?php

namespace App\Http\Services;


use App\Http\Requests\LikeRequest;
use App\Http\Resources\JobVacancyResource;
use App\Http\Resources\UserResource;
use App\Models\JobVacancy;
use App\Models\Like;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse as Response;

class LikeService
{
    private array $possible_like_types = ['job', 'user'];

    public function like(LikeRequest $request): Response
    {
        if (in_array($request->type, $this->possible_like_types)) {
            $like = Like::where('user_id', Auth::user()->getKey())
                ->where('liked_id', $request->liked_id)
                ->where('liked_type', $request->type === 'job' ? 'App\Models\JobVacancy' : 'App\Models\User')
                ->first();

            if ($like) {
                $like->delete();

                return response()->json([
                    'status' => true,
                    'message' => 'Disliked',
                ], 200);
            }

            if ($request->type == 'job') {
                $attachable = JobVacancy::findOrFail($request->liked_id);
            } else {
                $attachable = User::findOrFail($request->liked_id);
            }

            $attachable->likedByUsers()->attach(Auth::user()->id);

            return response()->json([
                'status' => true,
                'message' => 'Liked',
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Bad request',
        ], 400);
    }

    public function getLikedJobs(): AnonymousResourceCollection
    {
        return JobVacancyResource::collection(Auth::user()->likedJobs);
    }

    public function getLikedUsers(): AnonymousResourceCollection
    {
        return UserResource::collection(Auth::user()->likedUsers);
    }
}
