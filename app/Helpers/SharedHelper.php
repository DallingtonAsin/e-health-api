<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Storage;
use App\Models\Patient;
use App\Helpers\Globals as Globals;
use Haruncpi\LaravelIdGenerator\IdGenerator;


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
            $user = Patient::find($user_id);
            $user->access_token = $user->createToken('User'.$user->country_code.''.$user->phone_number, ['user'])->accessToken;
            if(!empty($user->image)){
                $user->image = Storage::disk('appImages')->url($user->image);
            }
            return $user;
        }catch(\Exception $ex){
            throw $ex;
        }
    }

    public static function generateToken($user)
    {
        try {
            $phone_number = $user->country_code . '' . $user->phone_number;
            $access_token = $user->createToken('User' . $phone_number, ['user'])->accessToken;
            return $access_token;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function generateUniqueNumber($table, $column = null, $length, $prefix)
    {
      try {
  
        $config = ['table' => $table, 'length' => $length, 'prefix' => $prefix];

        if ($column != null) {
          $config['field'] = $column;
        }
        return IdGenerator::generate($config);
        
      } catch (\Exception $ex) {
        throw $ex;
      }
    }

}