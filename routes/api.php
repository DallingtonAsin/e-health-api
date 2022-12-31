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

Route::post('/user/login', [AuthenticationController::class, 'sendVerificationCode']);

Route::group(['prefix' => 'user', 'middleware' => ['auth:api']], function(){

    Route::post('verify', [AuthenticationController::class, 'verifyOTP']);
    Route::post('register', [AuthenticationController::class, 'register']);
    
});



// Route::apiResource('projects', ProjectController::class)->middleware('auth:api');
