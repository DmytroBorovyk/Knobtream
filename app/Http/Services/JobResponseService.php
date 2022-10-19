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

    public function show(JobVacancyResponse $response): JobVacancyResponseResource
    {
        $response->load(['vacancy', 'owner']);

        return new JobVacancyResponseResource($response);
    }

    public function create(ResponseOperationRequest $request, MailService $mail_service): JobVacancyResponseResource|Response
    {
        if (Auth::user()->balance - env('RESPONSE_CREATION_COST') >= 0) {
            $created_responses = JobVacancyResponse::where('job_id', $request->job_id)
                ->where('user_id', Auth::user()->getKey())
                ->count();


            if ($created_responses === 0) {
                $vacancy = JobVacancy::findOrFail($request->job_id);
                if ($vacancy->user_id !== Auth::user()->getKey()) {
                    $data = $request->validated();
                    $data['user_id'] = Auth::user()->getKey();
                    $response = JobVacancyResponse::create($data);

                    $vacancy->addReviewsCount();

                    Auth::user()->removeCoins(env('RESPONSE_CREATION_COST'));

                    $mail_service->check($vacancy);

                    return new JobVacancyResponseResource($response);
                }

                return response()->json([
                    'status' => false,
                    'message' => 'Response for own jobs is unable',
                ], 400);
            }

            return response()->json([
                'status' => false,
                'message' => 'Response for this job have been already created',
            ], 400);
        }

        return response()->json([
            'status' => false,
            'message' => 'Not enough coins',
        ], 400);
    }

    public function update(string $id, ResponseOperationRequest $request): JobVacancyResponseResource|Response
    {
        $response = JobVacancyResponse::findOrFail($id);

        if ($response->user_id == Auth::user()->getKey()) {
            $response->update($request->validated());

            return new JobVacancyResponseResource($response);
        }

        return response()->json([
            'status' => false,
            'message' => 'This job response does not belong to user',
        ], 422);
    }

    public function delete(JobVacancyResponse $response): Response
    {
        if ($response->user_id == Auth::user()->getKey()) {
            $vacancy_id = $response->job_id;
            $response->delete();

            $vacancy = JobVacancy::findOrFail($vacancy_id);
            $vacancy->removeReviewsCount();

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
