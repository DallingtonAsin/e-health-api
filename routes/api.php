<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\MedicalSpecialtyController;
use App\Http\Controllers\MedicalDoctorController;
use App\Http\Controllers\AppointmentTypeController;
use App\Http\Controllers\MedicalAppointmentController;
use App\Http\Controllers\Auth\Patient\AuthenticationController as PatientAuthenticationController;
use App\Http\Controllers\Auth\Doctor\AuthenticationController as DoctorAuthenticationController;


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

Route::post('/patient/login', [PatientAuthenticationController::class, 'sendVerificationCode']);
Route::post('/doctor/login', [DoctorAuthenticationController::class, 'sendVerificationCode']);

Route::group(['prefix' => 'patient', 'middleware' => ['auth:patient']], function () {
    Route::post('verify', [PatientAuthenticationController::class, 'verifyOTP']);
    Route::post('register', [PatientController::class, 'register']);
    Route::post('profile/update', [PatientController::class, 'update']);
});

Route::group(['prefix' => 'doctor', 'middleware' => ['auth:doctor']], function () {
    Route::post('verify', [DoctorAuthenticationController::class, 'verifyOTP']);
    Route::post('register', [MedicalDoctorController::class, 'register']);
    Route::post('profile/update', [MedicalDoctorController::class, 'update']);
});

Route::group(['prefix' => 'medical', 'middleware' => ['auth:patient']], function () {
    Route::get('doctors/specialty/{specialty}', [MedicalDoctorController::class, 'getDoctorsBySpecialty']);
    Route::resource('specialties', MedicalSpecialtyController::class);
    Route::resource('doctors', MedicalDoctorController::class);
});

Route::group(['prefix' => 'appointments', 'middleware' => ['auth:patient']], function () {

    Route::resource('/', MedicalAppointmentController::class);
    Route::post('/', [MedicalAppointmentController::class, 'store'])->middleware('throttle:1,1');
    Route::put('/cancel', [MedicalAppointmentController::class, 'cancelAppointment']);
    Route::resource('types', AppointmentTypeController::class);

    Route::group(['prefix' => 'patient'], function () {
        Route::get('/{patient_id}/pending', [MedicalAppointmentController::class, 'getPatientPendingAppointments']);
        Route::get('/{patient_id}/confirmed', [MedicalAppointmentController::class, 'getPatientConfirmedAppointments']);
        Route::get('/{patient_id}/completed', [MedicalAppointmentController::class, 'getPatientCompletedAppointments']);
        Route::get('/{patient_id}/cancelled', [MedicalAppointmentController::class, 'getPatientCancelledAppointments']);
    });
});