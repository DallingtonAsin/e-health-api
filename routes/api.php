<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Patients\PatientController;
use App\Http\Controllers\Patients\PatientNotificationController;
use App\Http\Controllers\Doctors\MedicalDoctorController;
use App\Http\Controllers\Doctors\DoctorNotificationController;
use App\Http\Controllers\Doctors\DoctorScheduleController;
use App\Http\Controllers\Doctors\DoctorRatingController;
use App\Http\Controllers\Appointments\AppointmentTypeController;
use App\Http\Controllers\Appointments\MedicalAppointmentController;
use App\Http\Controllers\Medical\DrugController;
use App\Http\Controllers\Medical\MedicalSpecialtyController;
use App\Http\Controllers\Medical\MedicalHistoryController;
use App\Http\Controllers\Medical\MedicalFacilityController;
use App\Http\Controllers\Emails\MailController;
use App\Http\Controllers\Languages\LanguageController;
use App\Http\Controllers\Calls\CallController;
use App\Http\Controllers\Auth\Patient\AuthenticationController as PatientAuthenticationController;
use App\Http\Controllers\Auth\Doctor\AuthenticationController as DoctorAuthenticationController;
use App\Http\Controllers\Lab\LabTestCategoryController;
use App\Http\Controllers\Lab\ImageTestCategoryController;
use App\Http\Controllers\IcdCodes\IcdCodeController;
use App\Http\Controllers\Medical\AdministrationRouteController;
use App\Http\Controllers\Company\CompanyInfoController;

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

// Development routes
Route::post('send-sms', [PatientAuthenticationController::class, 'sendSms']);

Route::post('patient/login', [PatientAuthenticationController::class, 'login']);
Route::post('doctor/login', [DoctorAuthenticationController::class, 'login']);

Route::post('patient/sms/verification-code', [PatientAuthenticationController::class, 'sendVerificationCode']);
Route::post('doctor/sms/verification-code', [DoctorAuthenticationController::class, 'sendVerificationCode']);

Route::post('send/pending-appointment-emails', [MailController::class, 'sendPendingAppointmentMail']);

Route::group(['prefix' => 'patient', 'middleware' => ['auth:patient']], function () {

    Route::post('verify', [PatientAuthenticationController::class, 'verifyOTP']);
    Route::post('register', [PatientController::class, 'register']);
    Route::put('profile/update', [PatientController::class, 'update']);
    Route::post('profile-picture/update', [PatientController::class, 'updateProfilePicture']);
    Route::delete('profile-picture/delete', [PatientController::class, 'removeProfilePicture']);
    Route::post('rate-doctor', [DoctorRatingController::class, 'postRating']);
    Route::post('calls', [CallController::class, 'recordPatientDuration']);

    Route::get('notifications', [PatientNotificationController::class, 'getNotifications']);
    Route::get('notifications/read', [PatientNotificationController::class, 'getReadNotifications']);
    Route::get('notifications/unread', [PatientNotificationController::class, 'getUnReadNotifications']);
    Route::post('notifications/mark-as-read/{id}', [PatientNotificationController::class, 'markAsRead']);

    Route::post('favourite-doctor', [PatientController::class, 'markDoctorAsFavourite']);
    Route::delete('favourite-doctor/{doctor_id}', [PatientController::class, 'unmarkDoctorAsFavourite']);
});

Route::group(['prefix' => 'doctor', 'middleware' => ['auth:doctor']], function () {
    Route::post('verify', [DoctorAuthenticationController::class, 'verifyOTP']);
    Route::post('register', [MedicalDoctorController::class, 'register']);
    Route::post('profile/complete', [MedicalDoctorController::class, 'completeRegistration']);
    Route::get('is-verified', [MedicalDoctorController::class, 'isVerified']);
    Route::get('languages', [LanguageController::class, 'getDoctorLanguages']);
    Route::get('specialties', [MedicalSpecialtyController::class, 'getDoctorSpecialties']);
    Route::put('profile/update', [MedicalDoctorController::class, 'update']);
    Route::post('profile-picture/update', [MedicalDoctorController::class, 'updateProfilePicture']);
    Route::delete('profile-picture/delete', [MedicalDoctorController::class, 'removeProfilePicture']);
    Route::post('calls', [CallController::class, 'recordDoctorDuration']);

    Route::get('notifications', [DoctorNotificationController::class, 'getNotifications']);
    Route::get('notifications/read', [DoctorNotificationController::class, 'getReadNotifications']);
    Route::get('notifications/unread', [DoctorNotificationController::class, 'getUnReadNotifications']);
    Route::post('notifications/mark-as-read/{id}', [DoctorNotificationController::class, 'markAsRead']);
});

Route::middleware(['auth:doctor'])->group(function () {
    Route::resource('labtest-categories', LabTestCategoryController::class);
    Route::resource('imagetest-categories', ImageTestCategoryController::class);
    Route::resource('icd-10-codes', IcdCodeController::class);

});

Route::group(['prefix' => 'medical', 'middleware' => ['auth:patient,doctor', 'patient.or.doctor']], function () {
    Route::get('doctors/specialty/{specialty}', [MedicalDoctorController::class, 'getDoctorsBySpecialty']);
    Route::resource('doctors', MedicalDoctorController::class);
    Route::resource('specialties', MedicalSpecialtyController::class);
    Route::resource('administration-routes', AdministrationRouteController::class);
    Route::resource('facilities', MedicalFacilityController::class);
    Route::get('appointments/{id}', [MedicalAppointmentController::class, 'show']);
    Route::get('appointments/{id}/status', [MedicalAppointmentController::class, 'checkAppointmentStatus']);
    Route::get('doctors/status/{is_online}', [MedicalDoctorController::class, 'getDoctorsByOnlineStatus']);
});

Route::middleware(['auth:patient,doctor', 'patient.or.doctor'])->group(function () {
    Route::resource('drugs', DrugController::class);
    Route::resource('company-information', CompanyInfoController::class);
    Route::get('appointments/meeting/{appointment_id}', [MedicalAppointmentController::class, 'getAppointmentMeetingDetails']);
    Route::get('medical-history/patient/{patient_id}', [MedicalHistoryController::class, 'getPatientMedicalHistory']);
    Route::get('prescription-drugs', [DrugController::class, 'getPrescriptionDrugs']);
});

Route::group(['prefix' => 'doctor', 'middleware' => ['auth:doctor']], function () {
    Route::resource('schedule', DoctorScheduleController::class);
    Route::get('{doctor_id}/pending', [MedicalAppointmentController::class, 'getDoctorPendingAppointments']);
    Route::get('{doctor_id}/confirmed', [MedicalAppointmentController::class, 'getDoctorConfirmedAppointments']);
    Route::get('{doctor_id}/completed', [MedicalAppointmentController::class, 'getDoctorCompletedAppointments']);
    Route::get('{doctor_id}/cancelled', [MedicalAppointmentController::class, 'getDoctorCancelledAppointments']);
    Route::put('online-status', [MedicalDoctorController::class, 'updateOnlineStatus']);
    Route::put('appointments/auto-approve', [MedicalDoctorController::class, 'updateAutoApproveAppointmentStatus']);
    Route::post('test-push-notification', [DoctorNotificationController::class, 'sendTestPushNotification']);
});


Route::group(['prefix' => 'appointments'], function () {

    Route::middleware(['auth:patient'])->group(function () {
        Route::resource('', MedicalAppointmentController::class);
        Route::post('', [MedicalAppointmentController::class, 'store'])->middleware('throttle:7,1');
        Route::put('cancel', [MedicalAppointmentController::class, 'cancelAppointment']);
        Route::resource('types', AppointmentTypeController::class);
    });

    Route::middleware(['auth:doctor'])->group(function () {
        Route::post('{appointment_id}/complete', [MedicalAppointmentController::class, 'completeAppointment']);
        Route::put('confirm', [MedicalAppointmentController::class, 'confirmAppointment']);
        Route::put('cancel', [MedicalAppointmentController::class, 'cancelAppointment']);
        Route::get('{appointment_id}/post-consultation-data', [MedicalAppointmentController::class, 'getAppointmentConsultationData']);
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
