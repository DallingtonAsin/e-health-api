<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthenticationController;

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

Route::post('/user/signup', [AuthenticationController::class, 'sendVerificationCode']);

Route::middleware('auth:api')->get('/user', function (Request $request) {

    Route::post('login', [AuthenticationController::class, 'verifyOTP']);
    Route::post('register', [AuthenticationController::class, 'register']);
   

    return $request->user();
});



// Route::apiResource('projects', ProjectController::class)->middleware('auth:api');
