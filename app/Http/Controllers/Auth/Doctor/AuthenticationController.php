<?php

namespace App\Http\Controllers\Auth\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\SharedHelper as Helper;
use App\Repositories\UserTypeRepository;
use App\Repositories\MedicalDoctorRepository;
use App\Repositories\DoctorAuthenticationRepository;
use App\Services\Transaction\Sms\SmsService;

class AuthenticationController extends Controller
{

    protected $doctorRepository, $doctorAuthRepository, $userTypeRepository, $smsService;

    public function __construct(
        MedicalDoctorRepository $doctorRepository,
        DoctorAuthenticationRepository $doctorAuthRepository,
        UserTypeRepository $userTypeRepository,
        SmsService $smsService
    ) {
        $this->doctorRepository = $doctorRepository;
        $this->doctorAuthRepository = $doctorAuthRepository;
        $this->userTypeRepository = $userTypeRepository;
        $this->smsService = $smsService;
    }


    // send verification code
    public function sendVerificationCode(Request $request)
    {

        $dataObj = [
            'country_code' => 'required',
            'phone_number' => 'required',
            'unique_device_id' => 'sometimes|nullable',
            'device_token' => 'sometimes|nullable',
            'ip_address' => 'sometimes|nullable',
            'current_version' => 'required',
        ];

        $validator = Validator::make($request->all(), $dataObj);

        try {

            if ($validator->fails()) {
                $message = $validator->errors()->all();
                return Helper::sendFailedHttpResponse($message);
            } else {

                $country_code = $request->country_code;
                $phone_number = $request->phone_number;
                $unique_device_id = $request->unique_device_id;
                $fcm_token = $request->device_token;
                $current_version = $request->current_version;
                $ip_address = $request->ip_address;

                $exists = $this->doctorRepository->checkIfPhoneNumberExists($country_code, $phone_number);

                if ($exists) {

                    $doctor = $this->doctorRepository->getDetailsByPhoneNumber($country_code, $phone_number);

                    $doctor->update([
                        'unique_device_id' => $unique_device_id,
                        'ip_address' => $ip_address,
                        'current_version' => $current_version,
                        'fcm_token' => $fcm_token
                    ]);
                } else {

                    $user_type_id = $this->userTypeRepository->getPatientTypeId();

                    $validatedData = [
                        'user_type_id' => $user_type_id,
                        'country_code' => $country_code,
                        'phone_number' => $phone_number,
                        'unique_device_id' => $unique_device_id,
                        'fcm_token' => $fcm_token,
                        'current_version' => $current_version,
                        'ip_address' => $ip_address,
                    ];

                    $doctor = $this->doctorRepository->create($validatedData);
                }

                if ($doctor->is_blocked) {
                    return Helper::sendFailedHttpResponse("Sorry, your account has been blocked. Please contact support for more information.");
                } else {
                    $data = $this->sendCode($doctor);
                    return Helper::sendOkHttpResponse($data);
                }
            }
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
            return Helper::sendFailedHttpResponse($message);
        }
    }

    // verify code
    public function verifyOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|min:4',
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->all();
            return Helper::sendFailedHttpResponse($message);
        } else {

            $doctor = auth('doctor')->user();
            $doctor_id = $doctor->id;
            $otp = $request->input('otp');

            $isValidOtp = $this->doctorRepository->isValidOTP($doctor_id, $otp);

            if ($isValidOtp) {
                $this->doctorRepository->update($doctor_id, ["otp" => null]);
                $doctor = $this->doctorRepository->generateAccessToken($doctor_id);
                return Helper::sendOkHttpResponse($doctor);
            } else {
                $message = 'Invalid verification code';
                return Helper::sendFailedHttpResponse($message);
            }
        }
    }


    // send code 
    private function sendCode($doctor)
    {
        try {

            $doctor_id = $doctor->id;
            $otp = $this->smsService->generateNumericOTP(6);
            $phone_number = $doctor->country_code . '' . $doctor->phone_number;
            // $this->smsService->sendOTP($phone_number, $otp);
            $this->doctorRepository->update($doctor_id, ["otp" => $otp]);
            $doctor = $this->doctorRepository->generateAccessToken($doctor_id);

            return $doctor;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    // login doctor
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_code' => 'required',
            'phone_number' => 'required',
            'otp' => 'required|min:4',
            'unique_device_id' => 'sometimes|nullable',
            'device_token' => 'sometimes|nullable',
            'ip_address' => 'sometimes|nullable',
            'current_version' => 'sometimes|nullable',
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->all();
            return Helper::sendFailedHttpResponse($message);
        } else {

            $country_code = $request->input('country_code');
            $phone_number = $request->input('phone_number');
            $otp = $request->input('otp');

            $unique_device_id = $request->unique_device_id;
            $fcm_token = $request->device_token;
            $ip_address = $request->ip_address;
            $current_version = $request->current_version;


            $exists = $this->doctorAuthRepository->isValidCode($country_code, $phone_number, $otp);

            if ($exists) {

                $isRegistered = $this->doctorRepository->checkIfPhoneNumberExists($country_code, $phone_number);

                if ($isRegistered) {

                    $doctor = $this->doctorRepository->getDetailsByPhoneNumber($country_code, $phone_number);

                    $doctor->update([
                        'unique_device_id' => $unique_device_id,
                        'ip_address' => $ip_address,
                        'fcm_token' => $fcm_token,
                        'current_version' => $current_version,
                    ]);
                } else {

                    $user_type_id = $this->userTypeRepository->getDoctorTypeId();

                    $validatedData = [
                        'user_type_id' => $user_type_id,
                        'country_code' => $country_code,
                        'phone_number' => $phone_number,
                        'unique_device_id' => $unique_device_id,
                        'fcm_token' => $fcm_token,
                        'current_version' => $current_version,
                        'ip_address' => $ip_address,
                    ];

                    $doctor = $this->doctorRepository->create($validatedData);
                }

                if ($doctor->is_blocked) {
                    return Helper::sendFailedHttpResponse("Sorry, your account has been blocked. Please contact support for more information.");
                } else {
                    $doctor = $this->doctorRepository->generateAccessToken($doctor->id);
                    return Helper::sendOkHttpResponse($doctor);
                }
            } else {
                $message = 'Invalid verification code. Contact admin if you have completely forgotten your code.';
                return Helper::sendFailedHttpResponse($message);
            }
        }
    }
}
