<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\MedicalDoctorRepository;
use App\Repositories\MedicalSpecialtyRepository;
use App\Repositories\DoctorIdentificationRepository;
use Illuminate\Support\Facades\Validator;
use App\Helpers\SharedHelper as Helper;
use App\Repositories\MedicalFacilityRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MedicalDoctorController extends Controller
{

    protected $doctorRepository, $medicalSpecialtyRepository, $doctorIdentificationRepository, $medicalFacilityRepository;

    public function __construct(
        MedicalDoctorRepository $doctorRepository,
        MedicalSpecialtyRepository $medicalSpecialtyRepository,
        DoctorIdentificationRepository $doctorIdentificationRepository,
        MedicalFacilityRepository $medicalFacilityRepository
    ) {
        $this->doctorRepository = $doctorRepository;
        $this->medicalSpecialtyRepository = $medicalSpecialtyRepository;
        $this->doctorIdentificationRepository = $doctorIdentificationRepository;
        $this->medicalFacilityRepository = $medicalFacilityRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $medical_doctors = $this->doctorRepository->get();
            return response()->json($medical_doctors, 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getDoctorsBySpecialty($specialty_id)
    {
        try {
            $medical_doctors = $this->doctorRepository->get(null, $specialty_id);
            return response()->json($medical_doctors, 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $doctor = $this->doctorRepository->get($id, null);
            return response()->json($doctor[0], 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    // register doctor
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:55',
            'last_name' => 'required|max:55',
            'email' => 'required|email|unique:medical_doctors',
            'gender' => 'required',
            'dob' => 'required|date',
            'password' => 'required|min:8|confirmed',
        ]);

        try {

            if ($validator->fails()) {
                $message = $validator->errors()->all();
                return Helper::sendFailedHttpResponse($message);
            } else {


                $dob = date('Y-m-d', strtotime($request->dob));
                $age = $this->getAge($dob);
                if ($age >=  18) {

                    $password = Hash::make($request->input('password'));
                    $validatedData = [
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'email' => $request->email,
                        'gender' => ucfirst($request->gender),
                        'dob' => $dob,
                        'password' => $password,
                        'profile_status' => 1
                    ];

                    $doctor = auth('doctor')->user();
                    $doctor_id = $doctor->id;
                    $this->doctorRepository->update($doctor_id, $validatedData);
                    $doctor = $this->doctorRepository->generateAccessToken($doctor_id);
                    return response()->json($doctor, 200);
                } else {
                    return Helper::sendFailedHttpResponse('You must be above 18 years to register as a doctor');
                }
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }


    // complete registration
    public function completeRegistration(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'specialty' => 'required|string|exists:medical_specialties,name',
            'primary_facility' => 'required|string|exists:medical_facilities,name',
            'other_facilities' => 'sometimes',
            'other_facilities.*' => 'sometimes|string|exists:medical_facilities,name',
            'address' => 'required|string',
            'bio_summary' => 'required|string',
            'qualification' => 'required|string',
            'training_institute' => 'required|string',
            'umdp_license_id' => 'required|string',
            'service_fee' => 'required|numeric',
            'front_image' => 'required',
            'back_image' => 'required'
        ]);

        try {

            if ($validator->fails()) {
                $message = $validator->errors()->all();
                return Helper::sendFailedHttpResponse($message);
            } else {

                $doctor = auth('doctor')->user();
                $doctor_id = $doctor->id;

                if ($request->hasFile('front_image') && $request->hasFile('back_image')) {

                    $front_image = $request->file('front_image');
                    $back_image = $request->file('back_image');

                    $front_image_extension = $front_image->getClientOriginalExtension();
                    $back_image_extension = $back_image->getClientOriginalExtension();
                    $front_image_path = $this->doctorIdentificationRepository->saveIdentificationFrontFile($doctor_id, $front_image, $front_image_extension);
                    $back_image_path = $this->doctorIdentificationRepository->saveIdentificationBackFile($doctor_id, $back_image, $back_image_extension);

                    $identificationData = [
                        'doctor_id' => $doctor_id,
                        'front' => $front_image_path,
                        'back' => $back_image_path
                    ];

                    $docs_exist = $this->doctorIdentificationRepository->checkIfDoctorDocsExist($doctor_id);
                    if ($docs_exist) {
                        $saveDocIdDetails = $this->doctorIdentificationRepository->updateByDoctorId($doctor_id, $identificationData);
                    } else {
                        $saveDocIdDetails = $this->doctorIdentificationRepository->create($identificationData);
                    }

                    if ($saveDocIdDetails) {

                        $specialty_name = $request->input('specialty');
                        $primary_facility = $request->input('primary_facility');
                        $address = $request->input('address');
                        $bio_summary = $request->input('bio_summary');
                        $qualification = $request->input('qualification');
                        $training_institute = $request->input('training_institute');
                        $umdp_license_id = $request->input('umdp_license_id');
                        $service_fee = floatval($request->input('service_fee'));

                        $specialty_details = $this->medicalSpecialtyRepository->getSpecialtyByName($specialty_name);
                        $facility_details = $this->medicalFacilityRepository->findByName($primary_facility);

                        $specialty_id = $specialty_details->id;
                        $primary_facility_id = $facility_details->id;

                        $profileData = [
                            'specialty_id' => $specialty_id,
                            'primary_facility_id' => $primary_facility_id,
                            'address' => $address,
                            'bio_summary' => $bio_summary,
                            'qualification' => $qualification,
                            'training_institute' => $training_institute,
                            'umdp_license_id' => $umdp_license_id,
                            'service_fee' => $service_fee,
                            'profile_status' => 1,
                            'is_registered' => 1
                        ];

                        if ($request->has('other_facilities')) {
                            $other_facilities = [];
                            $facilitiesArray = json_decode($request->input('other_facilities', true));
                            if (count($facilitiesArray) > 0) {
                                $myCollection = collect($facilitiesArray);
                                $myCollection->map(function ($facility) use (&$other_facilities) {
                                    $data = $this->medicalFacilityRepository->findByName($facility);
                                    $other_facilities[] = $data->id;
                                });
                                $profileData['other_facilities'] = serialize($other_facilities);
                            }
                        }

                        $this->doctorRepository->update($doctor_id, $profileData);
                        $doctor = $this->doctorRepository->generateAccessToken($doctor_id);
                        return response(['message' => 'Your profile has been submitted for review. You will be notified upon approval.', 'user' => $doctor], 200);
                    } else {
                        return Helper::sendFailedHttpResponse('System is unable to save your identification documents. Please try again later.');
                    }
                } else {
                    return Helper::sendFailedHttpResponse('System is unable to get your identification documents. Please try uploading the documents again.');
                }
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
            'address' => 'required',
            'gender' => 'required',
            'dob' => 'required',
            'email' => 'required|email',
            'specialty' => 'required|string|exists:medical_specialties,id',
            'primary_facility' => 'required|string|exists:medical_facilities,id',
            'other_facilities' => 'sometimes',
            'other_facilities.*' => 'sometimes|string|exists:medical_facilities,id',
            'qualification' => 'required',
            'training_institute' => 'required',
            'umdp_license_id' => 'required',
            'bio_summary' => 'required',
            'service_fee' => 'required|numeric',
            'update_front_id' => 'required',
            'update_back_id' => 'required'
        ]);

        try {

            if ($validator->fails()) {
                $message = $validator->errors()->all();
                return Helper::sendFailedHttpResponse($message);
            } else {

                $email = $request->email;
                $doctor = auth('doctor')->user();
                $doctor_id = $doctor->id;
                if ($email !== $doctor->email) {
                    $emailTaken = $this->doctorRepository->checkIfEmailExists($email);
                    if ($emailTaken) {
                        return response()->json(['error' => 'The email has already been taken.'], 500);
                    }
                }

                $update_front_id = $request->input('update_front_id');
                $update_back_id = $request->input('update_back_id');

                $doctorsDoc = $this->doctorIdentificationRepository->findByDoctorId($doctor_id);
                $front_image_path = $doctorsDoc->front;
                $back_image_path = $doctorsDoc->back;

                if ($update_front_id && $request->hasFile('front_image')) {
                    $front_image = $request->file('front_image');
                    $front_image_extension = $front_image->getClientOriginalExtension();
                    $front_image_path = $this->doctorIdentificationRepository->saveIdentificationFrontFile($doctor_id, $front_image, $front_image_extension);
                    $identificationData = [
                        'doctor_id' => $doctor_id,
                        'front' => $front_image_path
                    ];
                    $this->doctorIdentificationRepository->updateByDoctorId($doctor_id, $identificationData);
                }

                if ($update_back_id && $request->hasFile('back_image')) {
                    $back_image = $request->file('back_image');
                    $back_image_extension = $back_image->getClientOriginalExtension();
                    $back_image_path = $this->doctorIdentificationRepository->saveIdentificationBackFile($doctor_id, $back_image, $back_image_extension);
                    $identificationData = [
                        'doctor_id' => $doctor_id,
                        'back' => $back_image_path
                    ];
                    $this->doctorIdentificationRepository->updateByDoctorId($doctor_id, $identificationData);
                }

                $specialty_id = $request->input('specialty');
                $primary_facility_id = $request->input('primary_facility');

                $profileData = [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'address' => $request->address,
                    'gender' => ucfirst($request->gender),
                    'dob' => date('Y-m-d', strtotime($request->dob)),
                    'email' =>  $request->email,
                    'specialty_id' => $specialty_id,
                    'primary_facility_id' => $primary_facility_id,
                    'qualification' => $request->qualification,
                    'training_institute' => $request->training_institute,
                    'umdp_license_id' => $request->umdp_license_id,
                    'bio_summary' => $request->bio_summary,
                    'service_fee' => floatval($request->service_fee)
                ];

                if ($request->has('other_facilities')) {
                    $other_facilities = json_decode($request->input('other_facilities', true));
                    $profileData['other_facilities'] = serialize($other_facilities);
                }

                $this->doctorRepository->update($doctor_id, $profileData);
                $doctor = $this->doctorRepository->generateAccessToken($doctor_id);
                return response(['message' => 'Profile updated successfully', 'user' => $doctor], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function isVerified()
    {
        try {
            $authenticated_user = auth('doctor')->user();
            $doctor_id = $authenticated_user->id;
            $doctor = $this->doctorRepository->find($doctor_id);
            if ($doctor->is_verified) {
                $verified_doctor = $this->doctorRepository->generateAccessToken($doctor_id);
                return response()->json($verified_doctor, 200);
            } else {
                return Helper::sendFailedHttpResponse('Thank you for registering with us. Your profile is currently under review and you will be notified upon approval.');
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }


    public function updateProfilePicture(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required'
        ]);

        try {

            if ($validator->fails()) {
                $message = $validator->errors()->all();
                return Helper::sendFailedHttpResponse($message);
            } else {

                if ($request->hasFile('image')) {

                    $doctor = auth('doctor')->user();
                    $doctor_id = $doctor->id;
                    $file = $request->file('image');
                    $file_extension = $file->getClientOriginalExtension();

                    if (!is_null($doctor->image)) {
                        if (Storage::disk('public')->exists($doctor->image)) {
                            Storage::disk('public')->delete($doctor->image);
                        }
                    }

                    $filename = $doctor_id . '' . time() . '.' . $file_extension;
                    $filePath = $file->storeAs('images/doctors', $filename, 'public');
                    $input = ['image' => $filePath];
                    $hasUpdated = $this->doctorRepository->update($doctor_id, $input);

                    if ($hasUpdated) {
                        $doctor = $this->doctorRepository->generateAccessToken($doctor_id);
                        return response(['message' => 'Profile photo updated', 'user' => $doctor], 200);
                    } else {
                        return response()->json(['error' => "Technical problem while updating your profile picture"], 400);
                    }
                } else {
                    return Helper::sendFailedHttpResponse('System is unable to get your profile picture. Please try again!');
                }
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }


    public function removeProfilePicture()
    {
        try {
            $doctor = auth('doctor')->user();
            $id = $doctor->id;
            if (!is_null($doctor->image)) {
                if (Storage::disk('public')->exists($doctor->image)) {
                    Storage::disk('public')->delete($doctor->image);
                }
            }
            $hasUpdated = $this->doctorRepository->update($id, ['image' => null]);
            if ($hasUpdated) {
                $doctor = $this->doctorRepository->generateAccessToken($id);
                return Helper::sendOkHttpResponse(['message' => 'Profile picture has been removed successfully', 'user' => $doctor]);
            } else {
                $message = "Technical error while removing profile picture";
                return Helper::sendFailedHttpResponse($message);
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    private function getAge($birthdate)
    {
        try {
            $birthday = Carbon::parse($birthdate);
            $age = $birthday->age;
            return $age;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
