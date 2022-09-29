<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobCatalogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::get('/catalog', [JobCatalogController::class, 'index']);
Route::get('/catalog/show/{id}', [JobCatalogController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::post('/catalog/job', [JobCatalogController::class, 'create']);
    Route::put('/catalog/job/{id}', [JobCatalogController::class, 'update']);
    Route::delete('/catalog/job/{id}', [JobCatalogController::class, 'delete']);
    Route::get('/catalog/user-jobs', [JobCatalogController::class, 'userJobList']);
    Route::get('/auth/logout', [AuthController::class, 'logout']);
});
