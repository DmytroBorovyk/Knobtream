<?php

namespace App\Http\Services;

use App\Http\Requests\ResponseOperationRequest;
use App\Http\Resources\JobVacancyResponseResource;
use App\Models\JobVacancy;
use App\Models\JobVacancyResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse as Response;

class JobResponseService
{
    public function index(Request $request): Exception
    {
        return new Exception(501, 'Not implemented yet');
    }

    public function show(string $id): JobVacancyResponseResource
    {
        $response = JobVacancyResponse::with(['vacancy', 'owner'])->findOrFail($id);

        return new JobVacancyResponseResource($response);
    }

    public function create(ResponseOperationRequest $request): JobVacancyResponseResource
    {
        $response = new JobVacancyResponse();
        $response->fill($request->validated());
        $response->user_id = Auth::user()->getKey();
        $response->save();

        $vacancy = JobVacancy::findOrFail($request->job_id);
        $vacancy->response_count++;
        $vacancy->save();

        return new JobVacancyResponseResource($response);
    }

    public function update(string $id, ResponseOperationRequest $request): JobVacancyResponseResource|Response
    {
        $response = JobVacancyResponse::findOrFail($id);

        if ($response->user_id == Auth::user()->getKey()) {
            $response->fill($request->validated());
            $response->save();

            return new JobVacancyResponseResource($response);
        }

        return response()->json([
            'status' => false,
            'message' => 'This job response does not belong to user',
        ], 422);
    }

    public function delete(string $id): Response
    {
        $response = JobVacancyResponse::findOrFail($id);
        if ($response->user_id == Auth::user()->getKey()) {
            $vacancy_id = $response->job_id;
            $response->delete();

            $vacancy = JobVacancy::findOrFail($vacancy_id);
            $vacancy->response_count--;
            $vacancy->save();
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

    public function userResponseList(): AnonymousResourceCollection
    {
        $responses = JobVacancyResponse::where('user_id', Auth::user()->getKey())
            ->with('vacancy')
            ->get();

        return JobVacancyResponseResource::collection($responses);
    }
}