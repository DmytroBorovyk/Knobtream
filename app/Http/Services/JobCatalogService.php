<?php

namespace App\Http\Services;

use App\Http\Requests\JobOperationRequest;
use App\Http\Resources\JobVacancyResource;
use App\Models\JobVacancy;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse as Response;

class JobCatalogService
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $vacancies = JobVacancy::with(['owner', 'likes', 'tags'])->get();

        if ($request->dateFrom) {
            $vacancies = $vacancies->where('created_at', '>=', $request->dateFrom);
        }

        if ($request->dateTo) {
            $vacancies = $vacancies->where('created_at', '<=', $request->dateTo);
        }

        if ($request->orderBy) {
            $vacancies = $this->order($vacancies, $request);
        }

        if ($request->tags) {
            $vacancies = $this->filter($vacancies, $request->tags);
        }

        return JobVacancyResource::collection($vacancies);
    }

    public function filter($vacancies, string $tags)
    {
        $tags = explode(',', $tags);
        $vacancies = $vacancies->map(function($item) use ($tags){
            foreach ($item->tags->pluck('id') as $tag){
                if(in_array($tag, $tags)){
                    return $item;
                }
            }
        });

        return $vacancies->filter();
    }

    public function order($vacancies, $request)
    {
        if ($request->orderWay === 'asc') {
            return $vacancies->sortBy($request->orderBy);
        }

        return $vacancies->sortByDesc($request->orderBy);
    }

    public function show(string $id): JobVacancyResource
    {
        $vacancy = JobVacancy::with(['owner', 'responses', 'likes'])->findOrFail($id);

        return new JobVacancyResource($vacancy);
    }

    public function create(JobOperationRequest $request): JobVacancyResource|Response
    {
        if (Auth::user()->balance - 2 >= 0) {
            $vacancies_day_count = JobVacancy::withTrashed()
                ->whereDate('created_at', Carbon::today())
                ->where('user_id', Auth::user()->getKey())
                ->count();

            if ($vacancies_day_count < 2) {
                $data = $request->validated();
                $data['user_id'] = Auth::user()->getKey();
                $vacancy = JobVacancy::create($data);

                if ($request->tags) {
                    $vacancy->tags()->sync($request->tags);
                }

                Auth::user()->removeCoins(2);

                return new JobVacancyResource($vacancy);
            }

            return response()->json([
                'status' => false,
                'message' => 'User already created 2 vacancies for today',
            ], 400);
        }

        return response()->json([
            'status' => false,
            'message' => 'Not enough coins',
        ], 400);
    }

    public function update(string $id, JobOperationRequest $request): JobVacancyResource|Response
    {
        $vacancy = JobVacancy::findOrFail($id);

        if ($vacancy->user_id == Auth::user()->getKey()) {
            $vacancy->update($request->validated());

            if ($request->tags) {
                $vacancy->tags()->sync($request->tags);
            }

            return new JobVacancyResource($vacancy);
        }

        return response()->json([
            'status' => false,
            'message' => 'This job does not belong to user',
        ], 422);
    }

    public function delete(string $id): Response
    {
        $vacancy = JobVacancy::findOrFail($id);
        if ($vacancy->user_id == Auth::user()->getKey()) {
            $vacancy->delete();

            return response()->json([
                'status' => true,
                'message' => 'deleted',
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'This job does not belong to user',
        ], 422);
    }

    public function userJobList(): AnonymousResourceCollection
    {
        $vacancies = JobVacancy::where('user_id', Auth::user()->getKey())->get();

        return JobVacancyResource::collection($vacancies);
    }
}
