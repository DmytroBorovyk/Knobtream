<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse as Response;
use OpenApi\Annotations as OA;

class AuthController extends Controller
{
    /**
     * @OA\Info(title="Api", version="1")
     * @OA\SecurityScheme(
     *     type="http",
     *     description="Login with email and password to get the authentication token",
     *     name="Token based Based",
     *     in="header",
     *     scheme="bearer",
     *     bearerFormat="JWT",
     *     securityScheme="apiAuth",
     * )
     * @OA\Schema( schema="LoginRequest",
     *      @OA\Property(property="email", type="string", example="qwerty@gmail.com"),
     *      @OA\Property(property="password", type="string", example="qwerty"),
     *  )
     * @OA\Schema( schema="RegistrationRequest",
     *      @OA\Property(property="name", type="string", example="Joe Dow"),
     *      @OA\Property(property="email", type="string", example="qwerty@gmail.com"),
     *      @OA\Property(property="password", type="string", example="qwerty"),
     *  )
     * @OA\Schema( schema="SuccessAuthResponse",
     *      @OA\Property(property="status", type="boolean", example=true),
     *      @OA\Property(property="token", type="string", example="22f225e27731443c60800627b5e5540c240b3ba1d11fc959d340b0a45ca8845d"),
     *  )
     * @OA\Schema( schema="SuccessLogoutResponse",
     *      @OA\Property(property="status", type="boolean", example=true),
     *      @OA\Property(property="message", type="string", example="logout"),
     *  )
     * @OA\Schema( schema="ServerErrorResponse",
     *      @OA\Property(property="status", type="boolean", example=false),
     *      @OA\Property(property="token", type="string", example="Servrer error text"),
     *  )
     * @OA\Schema( schema="UnauthorizedResponse",
     *      @OA\Property(property="message", type="string", example="Unauthenticated."),
     *  )
     * @OA\Schema( schema="BadCredsResponse",
     *      @OA\Property(property="message", type="string", example="Bad credentials"),
     *  )
     * @OA\Post(
     *      path="/api/auth/login",
     *      operationId="LoginRequest",
     *      summary="Login request",
     *      tags={"Auth"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/SuccessAuthResponse"
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
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
    public function login(LoginRequest $request, AuthService $service): Response
    {
        return response()->json([
            'status' => true,
            'token' => $service->login($request),
        ], 200);
    }

    /**
     * @OA\Post(
     *      path="/api/auth/register",
     *      operationId="RegistrationRequest",
     *      summary="Registration request",
     *      tags={"Auth"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/RegistrationRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/SuccessAuthResponse"
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
    public function register(RegistrationRequest $request, AuthService $service): Response
    {
        return response()->json([
            'status' => true,
            'token' => $service->registration($request),
        ], 200);
    }

    /**
     * @OA\Get(
     *      path="/api/auth/logout",
     *      operationId="Logout",
     *      summary="Logout",
     *      tags={"Auth"},
     *      security={{"apiAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/SuccessLogoutResponse"
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
    public function logout(Request $request): Response
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'logout',
        ], 200);
    }

    /**
     * @OA\Get(
     *      path="/api/user",
     *      operationId="getUseInfo",
     *      summary="Get user information",
     *      tags={"User"},
     *      security={{"apiAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/UserResource"
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized operation",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/UnauthorizedResponse"
     *          )
     *      )
     *  )
     */
    public function user(Request $request): UserResource
    {
        return new UserResource($request->user());
    }
}
