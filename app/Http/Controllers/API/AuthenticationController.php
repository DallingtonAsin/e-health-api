<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Mockery\Exception;
use Illuminate\Support\Facades\Validator;
use App\Helpers\SharedHelper as Helper;
use App\Services\Transaction\Sms\SmsService;

class AuthenticationController extends Controller
{

    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:55',
            'last_name' => 'required|max:55',
            'email' => 'email|sometimes|nullable|unique:users',
            'gender' => 'required',
            'address' => 'required',
            'dob' => 'required'
        ]);

        try {

            if ($validator->fails()) {
                $message = $validator->errors()->all();
                return Helper::sendFailedHttpResponse($message);
            } else {

                $validatedData = [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'gender' => ucfirst($request->gender),
                    'address' => $request->address,
                    'dob' => date('Y-m-d', strtotime($request->dob)),
                    'profile_status' => 1,
                ];

                $user = auth('api')->user();
                $user_id = $user->id;
                User::where('id', $user_id)->update($validatedData);
                $user = User::find($user_id);
                return response($user, 201);
            }

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


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

                $exists = User::where("country_code", "=", $country_code)
                    ->where("phone_number", "=", $phone_number)
                    ->exists();

                if ($exists) {

                    $user = User::where("country_code", $country_code)
                        ->where("phone_number", $phone_number)
                        ->first();

                    $user->update([
                        'unique_device_id' => $unique_device_id,
                        'ip_address' => $ip_address,
                        'current_version' => $current_version,
                        'fcm_token' => $fcm_token
                    ]);

                } else {

                    $validatedData = [
                        'country_code' => $country_code,
                        'phone_number' => $phone_number,
                        'unique_device_id' => $unique_device_id,
                        'fcm_token' => $fcm_token,
                        'current_version' => $current_version,
                        'ip_address' => $ip_address,
                    ];
                   
                    $user = User::create($validatedData);
                }

                $data = $this->sendCode($user);
                return Helper::sendOkHttpResponse($data);

            }
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
            return Helper::sendFailedHttpResponse($message);
        }
    }

    public function verifyOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|min:4',
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->all();
            return Helper::sendFailedHttpResponse($message);
        } else {

            $user = auth('api')->user();
            $user_id = $user->id;
            $otp = request('otp');

            $exists = User::where("id", $user_id)->where("otp", "=", $otp)->exists();

            if ($exists) {
                $user = User::find($user_id);
                $user->update(["otp" => null]);
                return Helper::sendOkHttpResponse($user);

            } else {
                $message = 'Invalid verification code';
                return Helper::sendFailedHttpResponse($message);
            }

        }
    }

    private function sendCode($user)
    {
        try {

            $user_id = $user->id;
            $otp = $this->smsService->generateNumericOTP(4);
            $user_phone_number = $user->country_code . '' . $user->phone_number;
            $this->smsService->sendOTP($user_phone_number, $otp);

            User::where("id", $user_id)->update(["otp" => $otp]);
            $user = User::find($user_id);
            $user->access_token = $user->createToken('User' . $user_phone_number, ['user'])->accessToken;

            return $user;

        } catch (\Exception $ex) {
            throw $ex;
        }
    }


}