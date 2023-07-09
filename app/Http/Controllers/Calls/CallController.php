<?php

namespace App\Http\Controllers\Calls;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\SharedHelper as Helper;
use App\Repositories\Appointments\CallRepository;

class CallController extends Controller
{

    protected $callRepository;
    public function __construct(CallRepository $callRepository)
    {
        $this->callRepository = $callRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

    public function recordPatientDuration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:medical_doctors,id',
            'appointment_id' => 'required|exists:medical_appointments,id',
            'duration' => 'required|numeric',
        ]);

        try {

            if ($validator->fails()) {
                $message = $validator->errors()->all();
                return Helper::sendFailedHttpResponse($message);
            } else {

                $patient = auth('patient')->user();
                $patient_id  = $patient->id;
                $doctor_id  = $request->doctor_id;
                $appointment_id = $request->appointment_id;
                $duration = $request->duration;

                $criteria = [
                    'patient_id' => $patient_id,
                    'doctor_id' => $doctor_id,
                    'appointment_id' => $appointment_id
                ];

                $validatedData = [
                    'patient_id' => $patient_id,
                    'doctor_id' => $doctor_id,
                    'appointment_id' => $appointment_id,
                    'patient_duration' => $duration
                ];
                $this->callRepository->updateOrCreateCall($criteria, $validatedData);
                return response()->json(['Patient call details inserted successfully'], 200);
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }


    public function recordDoctorDuration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'required|exists:medical_appointments,id',
            'duration' => 'required|numeric',
        ]);

        try {

            if ($validator->fails()) {
                $message = $validator->errors()->all();
                return Helper::sendFailedHttpResponse($message);
            } else {

                $doctor = auth('doctor')->user();
                $doctor_id  = $doctor->id;
                $patient_id  = $request->patient_id;
                $appointment_id = $request->appointment_id;
                $duration = $request->duration;

                $criteria = [
                    'patient_id' => $patient_id,
                    'doctor_id' => $doctor_id,
                    'appointment_id' => $appointment_id
                ];

                $validatedData = [
                    'patient_id' => $patient_id,
                    'doctor_id' => $doctor_id,
                    'appointment_id' => $appointment_id,
                    'doctor_duration' => $duration
                ];
                $this->callRepository->updateOrCreateCall($criteria, $validatedData);
                return response()->json(['Doctor call details inserted successfully'], 200);
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }
}
