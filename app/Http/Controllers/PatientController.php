<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\SharedHelper as Helper;
use Illuminate\Support\Facades\Storage;
use App\Models\Patient;
use App\Repositories\PatientRepository;

class PatientController extends Controller
{

    protected $patientRepository;

    
    public function __construct(PatientRepository $patientRepository)
    {
        $this->patientRepository = $patientRepository;
    }

        // register patient
        public function register(Request $request)
        {
    
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|max:55',
                'last_name' => 'required|max:55',
                'email' => 'email|sometimes|nullable|unique:patients',
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
    
                    $patient = auth('patient')->user();
                    $patient_id = $patient->id;
                    $this->patientRepository->update($patient_id, $validatedData);
                    $patient = $this->patientRepository->generateAccessToken($patient_id);
                    return response($patient, 200);
                }
            } catch (\Exception $ex) {
                return response()->json(['error' => $ex->getMessage()], 500);
            }
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
                $patient = auth('patient')->user();
                $patient_id = $patient->id;
                $patientDetail = Patient::find($patient_id);
                
                if($email != $patientDetail->email){
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

                $this->patientRepository->update($patient_id, $validatedData);
                $patient = $this->patientRepository->generateAccessToken($patient_id);
                return response(['message' => 'Profile updated successfully', 'user' => $patient], 200);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateProfilePicture(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:patients,id',
            'image' => 'required',
            'extension' => 'required'
        ]);

        try {

            if ($validator->fails()) {
                $message = $validator->errors()->all();
                return Helper::sendFailedHttpResponse($message);
            } else {

                $patient_id = $request->input('id');
                $imageData = $request->input('image');
                $image = base64_decode($imageData);
                $file_extension = $request->input('extension');

                $exists = $this->patientRepository->exists($patient_id);
                if ($exists) {

                    $patient = $this->patientRepository->find($patient_id);
                    if (!empty($patient->image)) {
                        Storage::disk('public')->delete($patient->image);
                    }

                    $filename = $patient_id . '' . time() . '.' . $file_extension;
                    Storage::disk('public')->put($filename, $image);

                    $input = ['image' => $filename];
                    $hasUpdated = $this->patientRepository->update($patient_id, $input);

                    if ($hasUpdated) {
                        $patient = $this->patientRepository->generateAccessToken($patient_id);
                        return response(['message' => 'Profile updated successfully', 'user' => $patient], 200);
                    } else {
                        return response()->json(['error' => "Technical problem while updating your profile picture"], 400);
                    }
                } else {
                    return response()->json(['error' => "System is unable to get your identity"], 400);
                }
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }


    public function removeProfilePicture($id)
    {

        try {

            $exists = $this->patientRepository->exists($id);

            if ($exists) {
                $patient = $this->patientRepository->find($id);
                if (!empty($patient->image)) {
                    Storage::disk('public')->delete($patient->image);
                }
                $hasUpdated = $this->patientRepository->update($id, ['image' => null]);
                if ($hasUpdated) {
                    $patient = $this->patientRepository->generateAccessToken($id);
                    return Helper::sendOkHttpResponse(['message' => 'Profile picture has been removed successfully', 'user' => $patient]);
                } else {
                    $message = "Technical error while removing profile picture";
                    return Helper::sendFailedHttpResponse($message);
                }
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

}