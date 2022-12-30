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

        $validatedData = $request->validate([
            'first_name' => 'required|max:55',
            'last_name' => 'required|max:55',
            'country_code' => 'required|max:5',
            'phone_number' => 'required|max:15',
            'email' => 'email|required|unique:users',
            'gender' => 'required',
            'address' => 'required',
            'dob' => 'required'
        ]);

        try {

            $user = User::create($validatedData);

            $accessToken = $user->createToken('authToken')->accessToken;

            return response(['user' => $user, 'access_token' => $accessToken], 201);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {


        $loginData = $request->validate([
            'country_code' => 'required',
            'phone_number' => 'required',
            'otp' => 'required'
        ]);

        try {

            if (!auth()->attempt($loginData)) {
                return response(['message' => 'Invalid OTP code'], 400);
            }

            $accessToken = auth()->user()->createToken('authToken')->accessToken;

            return response(['user' => auth()->user(), 'access_token' => $accessToken]);

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
            'country_code' => 'required',
            'phone_number' => 'required',
            'otp' => 'required|min:4',
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->all();
            return Helper::sendFailedHttpResponse($message);
        } else {

            $country_code = request('country_code');
            $phone_number = request('phone_number');
            $otp = request('otp');

            $exists = User::where("country_code", "=", $country_code)
                ->where("phone_number", "=", $phone_number)
                ->where("otp", "=", $otp)->exists();

            if ($exists) {
                $user = User::where("country_code", "=", $country_code)
                    ->where("phone_number", "=", $phone_number)
                    ->where("otp", "=", $otp)->first();

                config(['auth.guards.api.provider' => 'user']);
                $user_id = $user->id;
                $user = User::select('users.*')->find($user_id);
                $userData = Helper::getUserInfo($user_id);

                $userData['id'] = $user->id;
                $userData['country_code'] = $user->country_code;
                $userData['phone_number'] = $user->phone_number;
                $userData['is_registered'] = $user->profile_status;
                $userData['access_token'] = $user->createToken('Customer' . $user->country_code . '' . $user->phone_number, ['user'])->accessToken;

                User::where("country_code", "=", $country_code)->where("phone_number", "=", $phone_number)->update(["otp" => null]);

                $message = 'OTP successfully verified!';
                return Helper::sendOkHttpResponse(['message' => $message, 'data' => $userData]);

            } else {
                $message = 'Invalid OTP Code';
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