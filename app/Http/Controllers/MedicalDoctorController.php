<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\MedicalDoctorRepository;
use App\Repositories\MedicalSpecialtyRepository;
use Illuminate\Support\Facades\Validator;
use App\Helpers\SharedHelper as Helper;


class MedicalDoctorController extends Controller
{

    protected $doctorRepository, $medicalSpecialtyRepository;

    public function __construct(MedicalDoctorRepository $doctorRepository, MedicalSpecialtyRepository $medicalSpecialtyRepository)
    {
        $this->doctorRepository = $doctorRepository;
        $this->medicalSpecialtyRepository = $medicalSpecialtyRepository;
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
            return response($medical_doctors, 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getDoctorsBySpecialty($speciality_id)
    {
        try {
            $medical_doctors = $this->doctorRepository->get(null, $speciality_id);
            return response($medical_doctors, 200);
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
            return response($doctor[0], 200);
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
            'service_fee' => 'required|numeric'
        ]);

        try {

            if ($validator->fails()) {
                $message = $validator->errors()->all();
                return Helper::sendFailedHttpResponse($message);
            } else {

                $specialty_name = $request->specialty;
                $specialty = $this->medicalSpecialtyRepository->getSpecialtyByName($specialty_name);

                $validatedData = [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'specialty' => $specialty->id,
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

                $doctor = auth('doctor')->user();
                $doctor_id = $doctor->id;
                $this->doctorRepository->update($doctor_id, $validatedData);
                $doctor = $this->doctorRepository->generateAccessToken($doctor_id);
                return response($doctor, 200);
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
            'service_fee' => 'required|numeric'
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

                $specialty_name = $request->specialty;
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
}