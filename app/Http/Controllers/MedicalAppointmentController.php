<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\SharedHelper as Helper;
use Illuminate\Support\Facades\Validator;
use App\Repositories\MedicalAppointmentRepository;
use App\Repositories\AppointmentTypeRepository;
use App\Repositories\MeetingTokenRepository;
use Carbon\Carbon;

class MedicalAppointmentController extends Controller
{


    protected $appointmentTypeRepository, $medicalAppointmentRepository, $meetingTokenRepository;


    public function __construct(AppointmentTypeRepository $appointmentTypeRepository,
                                MedicalAppointmentRepository $medicalAppointmentRepository,
                                MeetingTokenRepository $meetingTokenRepository)
    {
        $this->appointmentTypeRepository = $appointmentTypeRepository;
        $this->medicalAppointmentRepository = $medicalAppointmentRepository;
        $this->meetingTokenRepository = $meetingTokenRepository;
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

    public function getPatientAppointments($patient_id, $status = null)
    {
        try {
            return $this->medicalAppointmentRepository->getMedicalAppointments($patient_id, $status, null);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getPatientPendingAppointments($patient_id)
    {
        try {
            return $this->medicalAppointmentRepository->getMedicalAppointments($patient_id, 'pending', null);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getPatientConfirmedAppointments($patient_id)
    {
        try {
            return $this->medicalAppointmentRepository->getMedicalAppointments($patient_id, 'confirmed', null);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getPatientCompletedAppointments($patient_id)
    {
        try {
            return $this->medicalAppointmentRepository->getMedicalAppointments($patient_id, 'completed', null);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getPatientCancelledAppointments($patient_id)
    {
        try {
            return $this->medicalAppointmentRepository->getMedicalAppointments($patient_id, 'cancelled', null);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function cancelAppointment(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'appointment_number' => 'required|exists:medical_appointments,appointment_number'
        ]);

        try {

            if ($validator->fails()) {
                $message = $validator->errors()->all();
                return Helper::sendFailedHttpResponse($message);
            } else {

                $patient_id = $request->input('patient_id');
                $appointment_number = $request->input('appointment_number');

                $this->medicalAppointmentRepository->cancelAppointment($patient_id, $appointment_number);
                return response()->json(['message' => 'Appointment has been cancelled successfully'], 200);
            }
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
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
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

                    $appointment = $this->medicalAppointmentRepository->get($data->id);
                    $is_online = $appointment->isOnline();
                    $patient = $appointment->patient;
              
                    if($is_online){
                        $is_video = $appointment->isVideo();
                        $meeting_token = $this->meetingTokenRepository->generateMeetingToken($patient, $appointment_number, $is_video);
                        $meeting_details = [
                            'appointment_id' => $data->id,
                            'app_id' => env('AGORA_APP_ID'),
                            'channel' => 'MeetingRoomStream', // $appointment_number,
                            'token' => $meeting_token
                        ];
                        $this->meetingTokenRepository->create($meeting_details);
                    }
                   
                    $datetime = Carbon::parse($data->appointment_date);
                    $data->appointment_date = $datetime->toDateString();
                    $data->appointment_time = date('H:i', strtotime($datetime->toTimeString()));

                    return response()->json($data, 200);
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


    public function getDoctorPendingAppointments($doctor_id)
    {
        try {
            return $this->medicalAppointmentRepository->getMedicalAppointments(null, 'pending', $doctor_id);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getDoctorConfirmedAppointments($doctor_id)
    {
        try {
            return $this->medicalAppointmentRepository->getMedicalAppointments(null, 'confirmed', $doctor_id);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getDoctorCompletedAppointments($doctor_id)
    {
        try {
            return $this->medicalAppointmentRepository->getMedicalAppointments(null, 'completed', $doctor_id);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getDoctorCancelledAppointments($doctor_id)
    {
        try {
            return $this->medicalAppointmentRepository->getMedicalAppointments(null, 'cancelled', $doctor_id);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }


    public function getAppointmentMeetingDetails($appointment_id){
        try {
            $meeting = $this->meetingTokenRepository->getMeetingDetails($appointment_id);
            return $meeting[0];
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

}
