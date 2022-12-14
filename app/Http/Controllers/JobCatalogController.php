<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobOperationRequest;
use App\Http\Resources\JobVacancyResource;
use App\Http\Services\JobCatalogService;
use App\Models\JobVacancy;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse as Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Annotations as OA;

class JobCatalogController extends Controller
{
    public function __construct(private JobCatalogService $service)
    {
    }

    /**
     * @OA\Schema(schema="CatalogRequest",
     *      description="each field is not required",
     *      @OA\Property(property="tags", type="string", example="1,2"),
     *      @OA\Property(property="dateFrom", type="string", example="2022-1-1"),
     *      @OA\Property(property="dateTo", type="string", example="2022-1-1"),
     *      @OA\Property(property="orderBy", type="string", example="created_at"),
     *      @OA\Property(property="orderWay", type="string", example="asc"),
     * )
     *
     * @OA\Get(
     *      path="/api/catalog",
     *      operationId="Catalog",
     *      summary="Catalog",
     *      tags={"JobVacancyCatalog"},
     *      @OA\RequestBody(
     *          required=false,
     *          @OA\JsonContent(ref="#/components/schemas/CatalogRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/JobVacancyResource")
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
    public function index(Request $request): AnonymousResourceCollection
    {
        return $this->service->index($request);
    }

    /**
     * @OA\Get(
     *      path="/api/catalog/{id}",
     *      operationId="CatalogItem",
     *      summary="Get catalog item",
     *      tags={"JobVacancyCatalog"},
     *      @OA\Parameter(
     *          name="id",
     *          description="Job id",
     *          required=true,
     *          in="path",
     *          example="1"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/JobVacancyResource")
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
    public function show(JobVacancy $vacancy, Request $request): JobVacancyResource
    {
        return $this->service->show($vacancy);
    }

    /**
     * @OA\Schema( schema="JobOperationRequest",
     *      @OA\Property(property="title", type="string", example="title"),
     *      @OA\Property(property="description", type="string", example="description"),
     *      @OA\Property(property="tags", type="array",
     *          @OA\Items(example="1")
     *      ),
     *  )
     * @OA\Schema( schema="MaxJobsCreatedResponse",
     *      @OA\Property(property="status", type="boolean", example=false),
     *      @OA\Property(property="message", type="string", example="User already created 2 vacancies for today"),
     *  )
     * @OA\Post(
     *      path="/api/catalog/job",
     *      operationId="CreateJob",
     *      summary="Create job vacancy",
     *      tags={"JobVacancyCatalog"},
     *      security={{"apiAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/JobOperationRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/JobVacancyResource"
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad operation",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/MaxJobsCreatedResponse"
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
    public function create(JobOperationRequest $request): JobVacancyResource|Response
    {
        return $this->service->create($request);
    }

    /**
     * @OA\Schema( schema="ItemDoesNotBelongsToUserResponse",
     *      @OA\Property(property="status", type="boolean", example=false),
     *      @OA\Property(property="token", type="string", example="This item does not belong to user"),
     *  )
     * @OA\Put(
     *      path="/api/catalog/job/{id}",
     *      operationId="UpdateJob",
     *      summary="Update job vacancy",
     *      tags={"JobVacancyCatalog"},
     *      security={{"apiAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Job id",
     *          required=true,
     *          in="path",
     *          example="1"
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/JobOperationRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/JobVacancyResource"
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
     *          description="Job does not belong to user",
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
    public function update(JobVacancy $vacancy, JobOperationRequest $request): JobVacancyResource
    {
        return $this->service->update($vacancy, $request);
    }

    /**
     * @OA\Schema( schema="ItemDeletedResponse",
     *      @OA\Property(property="status", type="boolean", example=true),
     *      @OA\Property(property="token", type="string", example="deleted"),
     *  )
     * @OA\Delete(
     *      path="/api/catalog/job/{id}",
     *      operationId="DeleteJob",
     *      summary="Delete job vacancy",
     *      tags={"JobVacancyCatalog"},
     *      security={{"apiAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Job id",
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
     *          description="Job does not belong to user",
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
    public function delete(JobVacancy $vacancy): Response
    {
        return $this->service->delete($vacancy);
    }

    /**
     * @OA\Get(
     *      path="/api/catalog/user-jobs",
     *      operationId="UserJobList",
     *      summary="Get user job list",
     *      tags={"JobVacancyCatalog"},
     *      security={{"apiAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/JobVacancyResource")
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
    public function userJobList(): AnonymousResourceCollection
    {
        return $this->service->userJobList();
    }
}
