<?php

namespace App\Http\Controllers\Auth\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\SharedHelper as Helper;
use App\Services\Transaction\Sms\SmsService;
use App\Repositories\UserTypeRepository;
use App\Repositories\PatientRepository;


class AuthenticationController extends Controller
{

    protected $smsService, $patientRepository, $userTypeRepository;

    public function __construct(SmsService $smsService, PatientRepository $patientRepository, UserTypeRepository $userTypeRepository)
    {
        $this->smsService = $smsService;
        $this->patientRepository = $patientRepository;
        $this->userTypeRepository = $userTypeRepository;
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

                $exists = $this->patientRepository->checkIfPhoneNumberExists($country_code, $phone_number);

                if ($exists) {

                    $patient = $this->patientRepository->getPatientDetailsByPhoneNumber($country_code, $phone_number);

                    $patient->update([
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

                    $patient = $this->patientRepository->create($validatedData);
                }

                if ($patient->is_blocked) {
                    return Helper::sendFailedHttpResponse("Sorry, your account has been blocked. Please contact support for more information.");
                } else {
                    $data = $this->sendCode($patient);
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

            $patient = auth('patient')->user();
            $patient_id = $patient->id;
            $otp = request('otp');

            $isValidOtp = $this->patientRepository->isValidOTP($patient_id, $otp);

            if ($isValidOtp) {
                $this->patientRepository->update($patient_id, ["otp" => null]);
                $patient = $this->patientRepository->generateAccessToken($patient_id);
                return Helper::sendOkHttpResponse($patient);
            } else {
                $message = 'Invalid verification code';
                return Helper::sendFailedHttpResponse($message);
            }
        }
    }


    // send code 
    private function sendCode($patient)
    {
        try {

            $patient_id = $patient->id;
            $otp = $this->smsService->generateNumericOTP(6);
            $patient_phone_number = $patient->country_code . '' . $patient->phone_number;
            // $this->smsService->sendOTP($patient_phone_number, $otp);

            $this->patientRepository->update($patient_id, ["otp" => $otp]);
            $patient = $this->patientRepository->generateAccessToken($patient_id);

            return $patient;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }


    public function sendSms(Request $request)
    {
        $dataObj = [
            'phone_number' => 'required',
            'message' => 'required'
        ];

        $validator = Validator::make($request->all(), $dataObj);

        try {

            if ($validator->fails()) {
                $message = $validator->errors()->all();
                return Helper::sendFailedHttpResponse($message);
            } else {
                $phone_number = $request->phone_number;
                $message = $request->message;
                $response = $this->smsService->sendMessage($phone_number, $message);
                return response()->json($response, 200);
            }
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
            return Helper::sendFailedHttpResponse($message);
        }
    }
}
