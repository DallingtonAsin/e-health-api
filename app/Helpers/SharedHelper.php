<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Helpers\Globals as Globals;
class SharedHelper
{

    public static function sendOkHttpResponse($data)
    {
        try {
            return response()->json($data);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function sendOkHttpMessage($message)
    {
        try {
            return response()->json(["message" => $message], Globals::$STATUS_CODE_SUCCESS);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }


    public static function sendFailedHttpResponse($message)
    {
            return response()->json(["message" => $message], Globals::$STATUS_CODE_ERROR);
    }

    public static function getUserInfo($user_id){
        try{
            $user = User::find($user_id);
            $user->access_token = $user->createToken('User'.$user->country_code.''.$user->phone_number, ['user'])->accessToken;
            if(!empty($user->image)){
                $user->image = Storage::disk('appImages')->url($user->image);
            }
            return $user;
        }catch(\Exception $ex){
            throw $ex;
        }
    }

}