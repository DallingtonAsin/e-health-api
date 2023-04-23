<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\MedicalDoctorRepository;
use App\Repositories\MedicalSpecialtyRepository;
use App\Repositories\DoctorIdentificationRepository;
use Illuminate\Support\Facades\Validator;
use App\Helpers\SharedHelper as Helper;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MedicalDoctorController extends Controller
{

    protected $doctorRepository, $medicalSpecialtyRepository, $doctorIdentificationRepository;

    public function __construct(
        MedicalDoctorRepository $doctorRepository,
        MedicalSpecialtyRepository $medicalSpecialtyRepository,
        DoctorIdentificationRepository $doctorIdentificationRepository
    ) {
        $this->doctorRepository = $doctorRepository;
        $this->medicalSpecialtyRepository = $medicalSpecialtyRepository;
        $this->doctorIdentificationRepository = $doctorIdentificationRepository;
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

    public function getDoctorsBySpecialty($speciality_id)
    {
        try {
            $medical_doctors = $this->doctorRepository->get(null, $speciality_id);
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
        ]);

        try {

            if ($validator->fails()) {
                $message = $validator->errors()->all();
                return Helper::sendFailedHttpResponse($message);
            } else {


                $dob = date('Y-m-d', strtotime($request->dob));
                $age = $this->getAge($dob);
                if ($age >=  18) {

                    $validatedData = [
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'email' => $request->email,
                        'gender' => ucfirst($request->gender),
                        'dob' => $dob,
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
            'specialty' => 'required|exists:medical_specialties,name',
            'title' => 'required',
            'address' => 'required',
            'qualification' => 'required',
            'profession' => 'required',
            'languages' => 'required',
            'experience' => 'required',
            'service_fee' => 'required|numeric',
            'front_image' => 'required',
            'back_image' => 'required',
            // 'id_front_extension' => 'required',
            // 'id_back_extension' => 'required'
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

                    $saveDocIdDetails = $this->doctorIdentificationRepository->create($identificationData);
                    if ($saveDocIdDetails) {

                        $specialty_name = $request->input('specialty');
                        $specialty = $this->medicalSpecialtyRepository->getSpecialtyByName($specialty_name);

                        $validatedData = [
                            'specialty_id' => $specialty->id,
                            'title' => $request->input('title'),
                            'address' => $request->input('address'),
                            'qualification' => $request->input('qualification'),
                            'profession' => $request->input('profession'),
                            'languages' => serialize($request->languages),
                            'experience' => $request->input('experience'),
                            'service_fee' => floatval($request->input('service_fee')),
                            'profile_status' => 1,
                            'is_registered' => 1
                        ];


                        $this->doctorRepository->update($doctor_id, $validatedData);
                        $doctor = $this->doctorRepository->generateAccessToken($doctor_id);
                        return response()->json($doctor, 200);
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
            'specialty' => 'required|exists:medical_specialties,name',
            'title' => 'required',
            'email' => 'required|email|unique:medical_doctors',
            'address' => 'required',
            'gender' => 'required',
            'qualification' => 'required',
            'profession' => 'required',
            'dob' => 'required',
            'languages' => 'required',
            'experience' => 'required',
            'service_fee' => 'required|numeric',
        ]);

        try {

            if ($validator->fails()) {
                $message = $validator->errors()->all();
                return Helper::sendFailedHttpResponse($message);
            } else {

                $email = $request->email;
                $doctor = auth('doctor')->user();
                $doctor_id = $doctor->id;
                $doctorDetail = $this->doctorRepository->find($doctor_id);

                if ($email != $doctorDetail->email) {
                    $emailTaken = $this->doctorRepository->checkIfEmailExists($email);
                    if ($emailTaken) {
                        return response()->json(['error' => 'The email has already been taken.'], 500);
                    }
                }

                $specialty_name = $request->input('specialty');
                $specialty = $this->medicalSpecialtyRepository->getSpecialtyByName($specialty_name);

                $validatedData = [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'specialty_id' => $specialty->id,
                    'title' => $request->title,
                    'email' => $request->email,
                    'address' => $request->address,
                    'gender' => ucfirst($request->gender),
                    'qualification' => $request->qualification,
                    'profession' => $request->profession,
                    'dob' => date('Y-m-d', strtotime($request->dob)),
                    'languages' => serialize($request->languages),
                    'experience' => $request->experience,
                    'service_fee' => floatval($request->service_fee),
                    'profile_status' => 1
                ];

                $this->doctorRepository->update($doctor_id, $validatedData);
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


    public function updateProfilePicture(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:medical_doctors,id',
            'image' => 'required',
            'extension' => 'required'
        ]);

        try {

            if ($validator->fails()) {
                $message = $validator->errors()->all();
                return Helper::sendFailedHttpResponse($message);
            } else {

                $doctor_id = $request->input('id');
                $file_extension = $request->input('extension');

                $exists = $this->doctorRepository->exists($doctor_id);
                if ($exists) {

                    $doctor = $this->doctorRepository->find($doctor_id);
                    if (!is_null($doctor->image)) {
                        if (Storage::disk('public')->exists($doctor->image)) {
                            Storage::disk('public')->delete($doctor->image);
                        }
                    }

                    $file = $request->file('image');
                    $filename = $doctor_id . '' . time() . '.' . $file_extension;
                    $filePath = $file->storeAs('images/doctors', $filename, 'public');

                    $input = ['image' => $filePath];
                    $hasUpdated = $this->doctorRepository->update($doctor_id, $input);

                    if ($hasUpdated) {
                        $doctor = $this->doctorRepository->generateAccessToken($doctor_id);
                        return response(['message' => 'Profile updated successfully', 'user' => $doctor], 200);
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

            $exists = $this->doctorRepository->exists($id);

            if ($exists) {
                $doctor = $this->doctorRepository->find($id);
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
