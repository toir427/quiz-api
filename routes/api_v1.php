<?php

use App\Http\Controllers\Api\v1\AnswerController;
use App\Http\Controllers\Api\v1\HomeController;
use App\Http\Controllers\Api\v1\ProfileController;
use App\Http\Controllers\Api\v1\QuestionController;
use App\Http\Controllers\Api\v1\QuestionnaireController;
use App\Http\Controllers\Api\v1\SurveyController;
use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\UserController;
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

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});

Route::middleware(['api', 'auth:api'])->group(function () {
    Route::prefix('profile')->group(function () {
        Route::get('/me', [ProfileController::class, 'me']);
        Route::put('/update', [ProfileController::class, 'update']);
        Route::put('/update-password', [ProfileController::class, 'updatePassword']);
    });
    Route::apiResource('survey', SurveyController::class);
    Route::apiResource('questionnaire', QuestionnaireController::class)->only(['index', 'store', 'show']);
    Route::apiResource('user', UserController::class);
    Route::put('user/{user}/update-password', [UserController::class, 'updatePassword']);
    Route::apiResource('question', QuestionController::class);
    Route::put('survey/{survey}/sort', [SurveyController::class, 'sort']);
    Route::apiResource('answer', AnswerController::class);
    Route::get('home/all', [HomeController::class, 'all']);
});

Route::fallback(function () {
    return response()->json([
                                'error' => ['Page Not Found'],
                                'success' => false,
                                'result' => null,
                                'status' => 404,
                            ], 404);
});
