<?php

namespace App\Http\Services;

use App\Http\Requests\JobOperationRequest;
use App\Http\Resources\JobVacancyResource;
use App\Models\JobVacancy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse as Response;

class JobCatalogService
{
    use AuthorizesRequests;

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

        $vacancies = $this->paginate($vacancies, $request->page, $request->perPage);

        return JobVacancyResource::collection($vacancies);
    }

    public function paginate($items, $page = 1, $perPage = 15,  $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function filter(Collection $vacancies, string $tags): Collection
    {
        $tags = explode(',', $tags);
        $vacancies = $vacancies->map(function ($item) use ($tags) {
            foreach ($item->tags->pluck('id') as $tag) {
                if (in_array($tag, $tags)) {
                    return $item;
                }
            }
        });

        return $vacancies->filter();
    }

    public function order(Collection $vacancies, Request $request): Collection
    {
        if ($request->orderWay === 'asc') {
            return $vacancies->sortBy($request->orderBy);
        }

        return $vacancies->sortByDesc($request->orderBy);
    }

    public function show(JobVacancy $vacancy): JobVacancyResource
    {
        $vacancy->load(['owner', 'responses', 'likes']);

        return new JobVacancyResource($vacancy);
    }

    public function create(JobOperationRequest $request): JobVacancyResource|Response
    {
        if (Auth::user()->balance - env('JOB_CREATION_COST') >= 0) {
            $vacancies_day_count = JobVacancy::withTrashed()
                ->whereDate('created_at', Carbon::today())
                ->where('user_id', Auth::user()->getKey())
                ->count();

            if ($vacancies_day_count < env('JOB_CREATION_PER_DAY')) {
                $data = $request->validated();
                $data['user_id'] = Auth::user()->getKey();
                $vacancy = JobVacancy::create($data);

                if ($request->tags) {
                    $vacancy->tags()->sync($request->tags);
                }

                Auth::user()->removeCoins(env('JOB_CREATION_COST'));

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

    public function update(JobVacancy $vacancy, JobOperationRequest $request): JobVacancyResource|Response
    {
        $this->authorize('update', $vacancy);

        $vacancy->update($request->validated());

        if ($request->tags) {
            $vacancy->tags()->sync($request->tags);
        }

        return new JobVacancyResource($vacancy);
    }

    public function delete(JobVacancy $vacancy): Response
    {
        $this->authorize('delete', $vacancy);
        $vacancy->delete();

        return response()->json([
            'status' => true,
            'message' => 'deleted',
        ], 200);
    }

    public function userJobList(): AnonymousResourceCollection
    {
        $vacancies = JobVacancy::where('user_id', Auth::user()->getKey())->get();

        return JobVacancyResource::collection($vacancies);
    }
}
