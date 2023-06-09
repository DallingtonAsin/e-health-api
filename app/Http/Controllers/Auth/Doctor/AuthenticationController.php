<?php

namespace App\Http\Controllers\Auth\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\SharedHelper as Helper;
use App\Repositories\UserTypeRepository;
use App\Repositories\MedicalDoctorRepository;
use App\Services\Transaction\Sms\SmsService;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{

    protected $doctorRepository, $userTypeRepository, $smsService;

    public function __construct(
        MedicalDoctorRepository $doctorRepository,
        UserTypeRepository $userTypeRepository,
        SmsService $smsService
    ) {
        $this->doctorRepository = $doctorRepository;
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
                    if ($doctor->profile_status == 1) {
                        return Helper::sendFailedHttpResponse("This phone number has already been used to create another account. Please try again with a different number.");
                    } else {
                        $doctor->update([
                            'unique_device_id' => $unique_device_id,
                            'ip_address' => $ip_address,
                            'current_version' => $current_version,
                            'fcm_token' => $fcm_token
                        ]);
                    }
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

        try {
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
        } catch (\Exception $ex) {
            throw $ex;
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
            'is_phone_number_login' => 'boolean|required',
            'country_code' => 'sometimes|nullable',
            'phone_number' => 'sometimes|nullable',
            'email' => 'sometimes|nullable|email',
            'password' => 'required',
            'unique_device_id' => 'sometimes|nullable',
            'device_token' => 'sometimes|nullable',
            'ip_address' => 'sometimes|nullable',
            'current_version' => 'sometimes|nullable',
        ]);

        try {
            if ($validator->fails()) {
                $message = $validator->errors()->all();
                return Helper::sendFailedHttpResponse($message);
            } else {

                $isPhoneNumberLogin = $request->input('is_phone_number_login');
                $password = $request->password;
                $unique_device_id = $request->unique_device_id;
                $fcm_token = $request->device_token;
                $current_version = $request->current_version;
                $ip_address = $request->ip_address;

                $data = [
                    'unique_device_id' => $unique_device_id,
                    'fcm_token' => $fcm_token,
                    'current_version' => $current_version,
                    'ip_address' => $ip_address
                ];

                if ($isPhoneNumberLogin) {
                    if ($request->filled('country_code') && $request->filled('phone_number')) {
                        $doctor = $this->doctorRepository->getDetailsByPhoneNumber($request->country_code, $request->phone_number);
                        return $this->authenticate($password, $data, $doctor);
                    } else {
                        return Helper::sendFailedHttpResponse("Invalid request: no phone number supplied");
                    }
                } else {
                    if ($request->filled('email')) {
                        $doctor = $this->doctorRepository->getDetailsByEmail($request->email);
                        return $this->authenticate($password, $data, $doctor);
                    } else {
                        return Helper::sendFailedHttpResponse("Invalid request: no email supplied");
                    }
                }
            }
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
            return Helper::sendFailedHttpResponse($message);
        }
    }

    private function authenticate($password, $data, $doctor)
    {
        try {
            if ($doctor && Hash::check($password, $doctor->password)) {
                if ($doctor->is_blocked) {
                    return Helper::sendFailedHttpResponse("Sorry, your account has been blocked. Please contact support for more information.");
                } else {
                    $this->doctorRepository->update($doctor->id, $data);
                    $auth_details = $this->getAccessDetails($doctor);
                    return Helper::sendOkHttpResponse($auth_details);
                }
            } else {
                return Helper::sendFailedHttpResponse("Invalid login details");
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    private function getAccessDetails($doctor)
    {
        try {
            $auth_data = $this->doctorRepository->generateAccessToken($doctor->id);
            return $auth_data;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
