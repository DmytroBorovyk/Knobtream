<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResponseOperationRequest;
use App\Http\Resources\JobVacancyResponseResource;
use App\Http\Services\JobResponseService;
use App\Http\Services\MailService;
use App\Models\JobVacancyResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse as Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Annotations as OA;

class JobVacancyResponseController extends Controller
{

    public function __construct(private JobResponseService $service, private MailService $mail_service)
    {
    }

    /**
     * @OA\Get(
     *      path="/api/response/show/{id}",
     *      operationId="ResponseItem",
     *      summary="Get response item",
     *      tags={"JobVacancyResponses"},
     *      @OA\Parameter(
     *          name="id",
     *          description="Response id",
     *          required=true,
     *          in="path",
     *          example="1"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/JobVacancyResponseResource")
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Server error operation",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/ServerErrorResponse"
     *          )
     *      )
     *  )
     */
    public function show(JobVacancyResponse $response, Request $request): JobVacancyResponseResource
    {
        return $this->service->show($response);
    }

    /**
     * @OA\Schema( schema="ResponseOperationRequest",
     *      @OA\Property(property="job_id", type="integer", example=1),
     *      @OA\Property(property="review_text", type="string", example="text"),
     *  )
     * @OA\Schema( schema="ResponseForJobAlreadyCreated",
     *      @OA\Property(property="status", type="boolean", example=false),
     *      @OA\Property(property="message", type="string", example="Response for this job have been already created"),
     *  )
     * @OA\Post(
     *      path="/api/response/",
     *      operationId="CreateResponse",
     *      summary="Create job Response",
     *      tags={"JobVacancyResponses"},
     *      security={{"apiAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/ResponseOperationRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/JobVacancyResponseResource"
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad operation",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/ResponseForJobAlreadyCreated"
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized operation",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/UnauthorizedResponse"
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Server error operation",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/ServerErrorResponse"
     *          )
     *      )
     *  )
     */
    public function create(ResponseOperationRequest $request): JobVacancyResponseResource|Response {
        return $this->service->create($request, $this->mail_service);
    }

    /**
     * @OA\Delete(
     *      path="/api/response/{id}",
     *      operationId="DeleteJobResponse",
     *      summary="Delete response",
     *      tags={"JobVacancyResponses"},
     *      security={{"apiAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Response id",
     *          required=true,
     *          in="path",
     *          example="1"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/ItemDeletedResponse"
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized operation",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/UnauthorizedResponse"
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Response does not belong to user",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/ItemDoesNotBelongsToUserResponse"
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Server error operation",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/ServerErrorResponse"
     *          )
     *      )
     *  )
     */
    public function delete(JobVacancyResponse $response): Response
    {
        return $this->service->delete($response);
    }

    /**
     * @OA\Get(
     *      path="/api/response/user-responses",
     *      operationId="UserResponseList",
     *      summary="Get user response list",
     *      tags={"JobVacancyResponses"},
     *      security={{"apiAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/JobVacancyResponseResource")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized operation",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/UnauthorizedResponse"
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Server error operation",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/ServerErrorResponse"
     *          )
     *      )
     *  )
     */
    public function userResponsesList(): AnonymousResourceCollection
    {
        return $this->service->userResponseList();
    }
}
