<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MedicalSpecialtyController;
use App\Http\Controllers\MedicalDoctorController;
use App\Http\Controllers\AppointmentTypeController;
use App\Http\Controllers\MedicalAppointmentController;

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
    Route::post('profile/update', [UserController::class, 'update']);

});

Route::group(['prefix' => 'medical', 'middleware' => ['auth:api']], function () {
    Route::get('doctors/specialty/{specialty}', [MedicalDoctorController::class, 'getDoctorsBySpecialty']);
    Route::resource('specialties', MedicalSpecialtyController::class);
    Route::resource('doctors', MedicalDoctorController::class);
});

Route::group(['prefix' => 'appointments', 'middleware' => ['auth:api']], function () {
    Route::resource('/', MedicalAppointmentController::class);
    Route::resource('types', AppointmentTypeController::class);
    // Route::get('/patient/{patient_id}/{status?}', [MedicalAppointmentController::class, 'getPatientAppointments']);
    Route::get('/patient/{patient_id}/pending', [MedicalAppointmentController::class, 'getPatientPendingAppointments']);
    Route::get('/patient/{patient_id}/confirmed', [MedicalAppointmentController::class, 'getPatientConfirmedAppointments']);
    Route::get('/patient/{patient_id}/completed', [MedicalAppointmentController::class, 'getPatientCompletedAppointments']);
    Route::get('/patient/{patient_id}/cancelled', [MedicalAppointmentController::class, 'getPatientCancelledAppointments']);
    Route::put('/cancel', [MedicalAppointmentController::class, 'cancelAppointment']);
});






// Route::apiResource('projects', ProjectController::class)->middleware('auth:api');
