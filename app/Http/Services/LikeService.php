<?php

namespace App\Http\Services;


use App\Http\Requests\LikeRequest;
use App\Http\Resources\JobVacancyResource;
use App\Http\Resources\UserResource;
use App\Models\Like;
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
                ->where('type', $request->type)
                ->first();

            if ($like) {
                $like->delete();

                return response()->json([
                    'status' => true,
                    'message' => 'Disliked',
                ], 200);
            }

            Like::create([
                'user_id' => Auth::user()->getKey(),
                'liked_id' => $request->liked_id,
                'type' => $request->type,
            ]);

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
        $job_likes = Auth::user()->likes->where('type', 'job');
        $liked_jobs = $job_likes->map(function ($item) {
            return $item->liked_job;
        }, []);

        return JobVacancyResource::collection($liked_jobs);
    }

    public function getLikedUsers(): AnonymousResourceCollection
    {
        $user_likes = Auth::user()->likes->where('type', 'user');

        $liked_users = $user_likes->map(function ($item) {
            return $item->liked_user;
        }, []);

        return UserResource::collection($liked_users);
    }
}
