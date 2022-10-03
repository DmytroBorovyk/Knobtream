<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use OpenApi\Annotations as OA;

class TagController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/tags",
     *      operationId="Tags",
     *      summary="Get vacancies tags",
     *      tags={"Tag"},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Property(property="id", type="string", example="1"),
     *                  @OA\Property(property="title", type="string", example="title"),
     *              )
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
    public function index()
    {
        return Tag::get();
    }
}
