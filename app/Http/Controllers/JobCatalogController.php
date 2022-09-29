<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobOperationRequest;
use App\Http\Resources\JobVacancyResource;
use App\Http\Services\JobCatalogService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse as Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class JobCatalogController extends Controller
{
    public function index(Request $request, JobCatalogService $service): AnonymousResourceCollection
    {
        return $service->index($request);
    }

    public function show(string $id, Request $request, JobCatalogService $service): JobVacancyResource
    {
        return $service->show($id);
    }

    public function create(JobOperationRequest $request, JobCatalogService $service): JobVacancyResource
    {
        return $service->create($request);
    }

    public function update(string $id, JobOperationRequest $request, JobCatalogService $service): JobVacancyResource
    {
        return $service->update($id, $request);
    }

    public function delete(string $id, JobCatalogService $service): Response
    {
        return $service->delete($id);
    }

    public function userJobList(JobCatalogService $service): AnonymousResourceCollection
    {
        return $service->userJobList();
    }
}
