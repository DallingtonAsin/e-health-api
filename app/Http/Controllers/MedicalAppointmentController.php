<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\SharedHelper as Helper;
use Illuminate\Support\Facades\Validator;
use App\Repositories\MedicalAppointmentRepository;
use App\Repositories\AppointmentTypeRepository;
use Carbon\Carbon;

class MedicalAppointmentController extends Controller
{


    protected $appointmentTypeRepository, $medicalAppointmentRepository;


    public function __construct(AppointmentTypeRepository $appointmentTypeRepository, MedicalAppointmentRepository $medicalAppointmentRepository)
    {
        $this->appointmentTypeRepository = $appointmentTypeRepository;
        $this->medicalAppointmentRepository = $medicalAppointmentRepository;
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

    public function getPatientAppointments(Request $request, $patient_id, $status = null){
        try{
          return $this->medicalAppointmentRepository->getMedicalAppointments($patient_id, $status, null);
        }catch(\Exception $ex){
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
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:medical_doctors,id',
            'appointment_type' => 'required|exists:appointment_types,name',
            'appointment_date' => 'required',
            'appointment_time' => 'required',
            'symptoms' => 'sometimes|nullable',
            'notes' => 'sometimes|nullable'
        ]);

        try {

            if ($validator->fails()) {
                $message = $validator->errors()->all();
                return Helper::sendFailedHttpResponse($message);
            } else {

                $patient_id = $request->input('patient_id');
                $doctor_id = $request->input('doctor_id');
                $appointment_type_name = $request->input('appointment_type');
                $date = $request->input('appointment_date');
                $time = $request->input('appointment_time');
                $symptoms = $request->input('symptoms');
                $notes = $request->input('notes');

                $appointment_type = $this->appointmentTypeRepository->getAppointmentTypeByName($appointment_type_name);
                $appointment_date = Carbon::parse($date . ' ' . $time);
                $appointment_type_id = $appointment_type->id;
                $exists = $this->medicalAppointmentRepository->checkIfAppointmentExists($patient_id, $doctor_id, $appointment_type_id, $appointment_date);

                if ($exists) {
                    return response(['error' => 'You have already booked an appointment with such details'], 400);
                } else {

                    $appointment_number = $this->medicalAppointmentRepository->generateAppointmentNumber();

                    $data = [
                        'patient_id' => $patient_id,
                        'doctor_id' => $doctor_id,
                        'appointment_number' => $appointment_number,
                        'appointment_type_id' => $appointment_type->id,
                        'appointment_date' => $appointment_date,
                        'symptoms' => $symptoms,
                        'notes' => $notes
                    ];

                    $data = $this->medicalAppointmentRepository->create($data);
                    $data->makeHidden(['id', 'created_at', 'updated_at']);
                    $datetime = Carbon::parse($data->appointment_date);
                    $data->appointment_date = $datetime->toDateString();
                    $data->appointment_time = date('H:i', strtotime($datetime->toTimeString()));

                    return response($data, 200);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
}