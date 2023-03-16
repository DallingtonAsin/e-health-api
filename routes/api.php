<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MailController;
use App\Http\Controllers\DrugController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MedicalSpecialtyController;
use App\Http\Controllers\MedicalDoctorController;
use App\Http\Controllers\PatientNotificationController;
use App\Http\Controllers\DoctorNotificationController;
use App\Http\Controllers\AppointmentTypeController;
use App\Http\Controllers\DoctorScheduleController;
use App\Http\Controllers\MedicalAppointmentController;
use App\Http\Controllers\MedicalHistoryController;
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

Route::post('patient/login', [PatientAuthenticationController::class, 'sendVerificationCode']);
Route::post('doctor/login', [DoctorAuthenticationController::class, 'login']);

Route::post('send/pending-appointment-emails', [MailController::class, 'sendPendingAppointmentMail']);


Route::group(['prefix' => 'patient', 'middleware' => ['auth:patient']], function () {

    Route::post('verify', [PatientAuthenticationController::class, 'verifyOTP']);
    Route::post('register', [PatientController::class, 'register']);
    Route::post('profile/update', [PatientController::class, 'update']);
    Route::post('{patient_id}/profile-picture', [PatientController::class, 'updateProfilePicture']);
    Route::delete('{patient_id}/profile-picture/delete', [PatientController::class, 'removeProfilePicture']);

    Route::get('notifications', [PatientNotificationController::class, 'getNotifications']);
    Route::get('notifications/read', [PatientNotificationController::class, 'getReadNotifications']);
    Route::get('notifications/unread', [PatientNotificationController::class, 'getUnReadNotifications']);
    Route::post('notifications/mark-as-read/{id}', [PatientNotificationController::class, 'markAsRead']);
});

Route::group(['prefix' => 'doctor', 'middleware' => ['auth:doctor']], function () {
    Route::get('languages', [LanguageController::class, 'getDoctorLanguages']);
    Route::get('specialties', [MedicalSpecialtyController::class, 'getDoctorSpecialties']);
    Route::post('register', [MedicalDoctorController::class, 'register']);
    Route::post('profile/update', [MedicalDoctorController::class, 'update']);
    Route::post('{doctor_id}/profile-picture', [MedicalDoctorController::class, 'updateProfilePicture']);
    Route::delete('{doctor_id}/profile-picture/delete', [MedicalDoctorController::class, 'removeProfilePicture']);

    Route::get('notifications', [DoctorNotificationController::class, 'getNotifications']);
    Route::get('notifications/read', [DoctorNotificationController::class, 'getReadNotifications']);
    Route::get('notifications/unread', [DoctorNotificationController::class, 'getUnReadNotifications']);
    Route::post('notifications/mark-as-read/{id}', [DoctorNotificationController::class, 'markAsRead']);
});

Route::group(['prefix' => 'medical', 'middleware' => ['auth:patient']], function () {
    Route::get('doctors/specialty/{specialty}', [MedicalDoctorController::class, 'getDoctorsBySpecialty']);
    Route::resource('specialties', MedicalSpecialtyController::class);
    Route::resource('doctors', MedicalDoctorController::class);
});


Route::middleware(['auth:patient,doctor', 'patient.or.doctor'])->group(function () {
    Route::resource('drugs', DrugController::class);
    Route::get('appointments/meeting/{appointment_id}', [MedicalAppointmentController::class, 'getAppointmentMeetingDetails']);
    Route::get('medical-history/patient/{patient_id}', [MedicalHistoryController::class, 'getPatientMedicalHistory']);
});

Route::group(['prefix' => 'doctor', 'middleware' => ['auth:doctor']], function () {
    Route::resource('schedule', DoctorScheduleController::class);
    Route::get('{doctor_id}/pending', [MedicalAppointmentController::class, 'getDoctorPendingAppointments']);
    Route::get('{doctor_id}/confirmed', [MedicalAppointmentController::class, 'getDoctorConfirmedAppointments']);
    Route::get('{doctor_id}/completed', [MedicalAppointmentController::class, 'getDoctorCompletedAppointments']);
    Route::get('{doctor_id}/cancelled', [MedicalAppointmentController::class, 'getDoctorCancelledAppointments']);
});


Route::group(['prefix' => 'appointments'], function () {

    Route::middleware(['auth:patient'])->group(function () {
        Route::resource('', MedicalAppointmentController::class);
        Route::post('', [MedicalAppointmentController::class, 'store'])->middleware('throttle:7,1');
        Route::put('cancel', [MedicalAppointmentController::class, 'cancelAppointment']);
        Route::resource('types', AppointmentTypeController::class);
    });

    Route::middleware(['auth:doctor'])->group(function () {
        Route::put('{appointment_id}/complete', [MedicalAppointmentController::class, 'completeAppointment']);
    });

    Route::group(['prefix' => 'patient', 'middleware' => ['auth:patient']], function () {
        Route::get('{patient_id}/pending', [MedicalAppointmentController::class, 'getPatientPendingAppointments']);
        Route::get('{patient_id}/confirmed', [MedicalAppointmentController::class, 'getPatientConfirmedAppointments']);
        Route::get('{patient_id}/completed', [MedicalAppointmentController::class, 'getPatientCompletedAppointments']);
        Route::get('{patient_id}/cancelled', [MedicalAppointmentController::class, 'getPatientCancelledAppointments']);
    });

    Route::group(['prefix' => 'doctor', 'middleware' => ['auth:doctor']], function () {

        Route::get('{doctor_id}/pending', [MedicalAppointmentController::class, 'getDoctorPendingAppointments']);
        Route::get('{doctor_id}/confirmed', [MedicalAppointmentController::class, 'getDoctorConfirmedAppointments']);
        Route::get('{doctor_id}/completed', [MedicalAppointmentController::class, 'getDoctorCompletedAppointments']);
        Route::get('{doctor_id}/cancelled', [MedicalAppointmentController::class, 'getDoctorCancelledAppointments']);
    });
});
