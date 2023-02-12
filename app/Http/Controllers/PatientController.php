<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\SharedHelper as Helper;
use App\Models\Patient;
use App\Repositories\PatientRepository;

class PatientController extends Controller
{

    protected $patientRepository;

    
    public function __construct(PatientRepository $patientRepository)
    {
        $this->patientRepository = $patientRepository;
    }

    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:55',
            'last_name' => 'required|max:55',
            'email' => 'email|sometimes|nullable',
            'gender' => 'required',
            'address' => 'required',
            'dob' => 'required'
        ]);

        try {

            if ($validator->fails()) {
                $message = $validator->errors()->all();
                return Helper::sendFailedHttpResponse($message);
            } else {

                $email = $request->email;
                $user = auth('api')->user();
                $user_id = $user->id;
                $userDetail = Patient::find($user_id);
                
                if($email != $userDetail->email){
                    $emailTaken = Patient::where('email', $email)->exists();
                    if($emailTaken){
                        return response()->json(['error' => 'The email has already been taken.'], 500);
                    }
                }

                $validatedData = [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $email,
                    'gender' => ucfirst($request->gender),
                    'address' => $request->address,
                    'dob' => date('Y-m-d', strtotime($request->dob))
                ];

                $this->patientRepository->update($user_id, $validatedData);
                $user = $this->patientRepository->generateAccessToken($user_id);
                return response(['message' => 'Profile updated successfully', 'user' => $user], 200);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    

}