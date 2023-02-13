<?php

namespace App\Http\Controllers\Auth\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\SharedHelper as Helper;
use App\Services\Transaction\Sms\SmsService;
use App\Repositories\UserTypeRepository;
use App\Repositories\MedicalDoctorRepository;
use App\Repositories\DoctorAuthenticationRepository;


class AuthenticationController extends Controller
{

    protected $doctorRepository, $doctorAuthRepository, $userTypeRepository;

    public function __construct(
        MedicalDoctorRepository $doctorRepository,
        DoctorAuthenticationRepository $doctorAuthRepository,
        UserTypeRepository $userTypeRepository
    ) {
        $this->doctorRepository = $doctorRepository;
        $this->doctorAuthRepository = $doctorAuthRepository;
        $this->userTypeRepository = $userTypeRepository;
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

                    $doctor = $this->doctorRepository->getDoctorDetailsByPhoneNumber($country_code, $phone_number);

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

                $doctor = $this->doctorRepository->generateAccessToken($doctor->id);

                return Helper::sendOkHttpResponse($doctor);
            } else {
                $message = 'Invalid verification code';
                return Helper::sendFailedHttpResponse($message);
            }
        }
    }
}
