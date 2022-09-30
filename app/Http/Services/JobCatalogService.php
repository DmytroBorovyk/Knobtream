<?php

namespace App\Http\Services;

use App\Http\Requests\JobOperationRequest;
use App\Http\Resources\JobVacancyResource;
use App\Models\JobVacancy;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse as Response;

class JobCatalogService
{
    public function index(Request $request): AnonymousResourceCollection
    {
        if ($request->orderBy) {
            $vacancies = JobVacancy::with('owner')
                ->orderBy($request->orderBy, $request->orderWay ?: 'ASC')
                ->paginate(20);
        } else {
            $vacancies = JobVacancy::with('owner')->paginate(20);
        }

        return JobVacancyResource::collection($vacancies);
    }

    public function show(string $id): JobVacancyResource
    {
        $vacancy = JobVacancy::with(['owner'])->findOrFail($id);

        return new JobVacancyResource($vacancy);
    }

    public function create(JobOperationRequest $request): JobVacancyResource
    {
        $vacancy = new JobVacancy();
        $vacancy->fill($request->validated());
        $vacancy->user_id = Auth::user()->getKey();
        $vacancy->save();

        return new JobVacancyResource($vacancy);
    }

    public function update(string $id, JobOperationRequest $request): JobVacancyResource | Response
    {
        $vacancy = JobVacancy::findOrFail($id);

        if ($vacancy->user_id == Auth::user()->getKey()) {
            $vacancy->fill($request->validated());
            $vacancy->save();

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