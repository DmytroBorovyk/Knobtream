<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobCatalogController;
use App\Http\Controllers\JobVacancyResponseController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\TagController;
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
Route::get('/tags', [TagController::class, 'index']);
Route::get('/catalog/show/{vacancy}', [JobCatalogController::class, 'show']);
Route::get('/response/show/{response}', [JobVacancyResponseController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::prefix('catalog')->group(function () {
        Route::post('job', [JobCatalogController::class, 'create']);
        Route::put('job/{vacancy}', [JobCatalogController::class, 'update']);
        Route::delete('job/{vacancy}', [JobCatalogController::class, 'delete']);
        Route::get('user-jobs', [JobCatalogController::class, 'userJobList']);
    });
    Route::prefix('response')->group(function () {
        Route::post('/', [JobVacancyResponseController::class, 'create']);
        Route::delete('{response}', [JobVacancyResponseController::class, 'delete']);
        Route::get('user-responses', [JobVacancyResponseController::class, 'userResponsesList']);
    });
    Route::post('/like-toggle', [LikeController::class, 'like']);
    Route::get('/liked-jobs', [LikeController::class, 'getLikedJobs']);
    Route::get('/liked-users', [LikeController::class, 'getLikedUsers']);

    Route::get('/auth/logout', [AuthController::class, 'logout']);
});
