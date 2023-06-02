<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\SharedHelper as Helper;
use Illuminate\Support\Facades\Validator;
use App\Repositories\MedicalAppointmentRepository;
use App\Repositories\AppointmentTypeRepository;
use App\Repositories\MeetingTokenRepository;
use App\Repositories\MedicalHistoryRepository;
use App\Services\NotificationService;
use App\Services\PushNotificationService;
use Carbon\Carbon;

class MedicalAppointmentController extends Controller
{

    protected $appointmentTypeRepository, $medicalAppointmentRepository,
        $meetingTokenRepository, $notificationService, $medicalHistoryRepository, $pushNotificationService;

    public function __construct(
        AppointmentTypeRepository $appointmentTypeRepository,
        MedicalAppointmentRepository $medicalAppointmentRepository,
        MeetingTokenRepository $meetingTokenRepository,
        NotificationService $notificationService,
        MedicalHistoryRepository $medicalHistoryRepository,
        PushNotificationService $pushNotificationService

    ) {
        $this->appointmentTypeRepository = $appointmentTypeRepository;
        $this->medicalAppointmentRepository = $medicalAppointmentRepository;
        $this->meetingTokenRepository = $meetingTokenRepository;
        $this->notificationService = $notificationService;
        $this->medicalHistoryRepository = $medicalHistoryRepository;
        $this->pushNotificationService = $pushNotificationService;
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
            return $this->medicalAppointmentRepository->getMedicalAppointments(null, $patient_id, $status, null);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getPatientPendingAppointments($patient_id)
    {
        try {
            return $this->medicalAppointmentRepository->getMedicalAppointments(null, $patient_id, 'pending', null);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getPatientConfirmedAppointments($patient_id)
    {
        try {
            return $this->medicalAppointmentRepository->getMedicalAppointments(null, $patient_id, 'confirmed', null);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getPatientCompletedAppointments($patient_id)
    {
        try {
            return $this->medicalAppointmentRepository->getMedicalAppointments(null, $patient_id, 'completed', null);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getPatientCancelledAppointments($patient_id)
    {
        try {
            return $this->medicalAppointmentRepository->getMedicalAppointments(null, $patient_id, 'cancelled', null);
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
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'reason' => 'sometimes|nullable',
            'past_medical_history' => 'sometimes|nullable',
            'current_treatment' => 'sometimes|nullable',

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
                $reason = $request->input('reason');
                $past_medical_history = $request->input('past_medical_history');
                $current_treatment = $request->input('current_treatment');

                $appointment_type = $this->appointmentTypeRepository->getAppointmentTypeByName($appointment_type_name);
                $appointment_date =  Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $time);
                $appointment_type_id = $appointment_type->id;
                $exists = $this->medicalAppointmentRepository->checkIfAppointmentExists($patient_id, $doctor_id, $appointment_type_id, $appointment_date);

                $isConflict = $this->medicalAppointmentRepository->isAppointmentConflict($doctor_id, $appointment_date);
                if ($isConflict) {
                    return response()->json(['message' => 'Another appointment is already booked at the selected time. Please choose a different time.'], 400);
                } else {
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
                            'reason' => $reason,
                        ];

                        $data = $this->medicalAppointmentRepository->create($data);
                        $data->makeHidden(['created_at', 'updated_at']);

                        $medical_history_data = [
                            'patient_id' => $patient_id,
                            'appointment_id' => $data->id,
                            'past_medical_history' => $past_medical_history,
                            'current_treatment' => $current_treatment
                        ];

                        $this->medicalHistoryRepository->create($medical_history_data);

                        $appointment = $this->medicalAppointmentRepository->get($data->id);
                        $is_online = $appointment->isOnline();
                        $patient = $appointment->patient;

                        if ($is_online) {
                            $is_video = $appointment->isVideo();
                            $meeting_token = $this->meetingTokenRepository->generateMeetingToken($patient, $appointment_number, $is_video);
                            $meeting_details = [
                                'appointment_id' => $data->id,
                                'app_id' => config('app.AGORA_APP_ID'),
                                'channel' => config('app.AGORA_CHANNEL_NAME'),
                                'token' => $meeting_token
                            ];
                            $this->meetingTokenRepository->create($meeting_details);
                        }

                        $datetime = Carbon::parse($data->appointment_date);
                        $data->appointment_date = $datetime->toDateString();
                        $data->appointment_time = date('H:i', strtotime($datetime->toTimeString()));
                        $this->notificationService->sendAppointmentBookedMessage($patient_id, $data);
                        $this->notificationService->sendDoctorNewAppointmentMessage($appointment->doctor_id, $data);

                        return response()->json($data, 200);
                    }
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
        try {
            $notification = $this->medicalAppointmentRepository->getMedicalAppointments($id, null, null, null);
            return $notification[0];
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
            return $this->medicalAppointmentRepository->getMedicalAppointments(null, null, 'pending', $doctor_id);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getDoctorConfirmedAppointments($doctor_id)
    {
        try {
            return $this->medicalAppointmentRepository->getMedicalAppointments(null, null, 'confirmed', $doctor_id);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getDoctorCompletedAppointments($doctor_id)
    {
        try {
            return $this->medicalAppointmentRepository->getMedicalAppointments(null, null, 'completed', $doctor_id);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getDoctorCancelledAppointments($doctor_id)
    {
        try {
            return $this->medicalAppointmentRepository->getMedicalAppointments(null, null, 'cancelled', $doctor_id);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }


    public function getAppointmentMeetingDetails($appointment_id)
    {
        try {
            $meeting = $this->medicalAppointmentRepository->getMedicalAppointments($appointment_id);
            return $meeting[0];
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }



    public function completeAppointment(Request $request, $appointment_id)
    {

        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'illness' => 'required',
            'diagnosis_date' => 'required|date',
            'treatment' => 'required',
        ]);


        try {

            if ($validator->fails()) {
                $message = $validator->errors()->all();
                return Helper::sendFailedHttpResponse($message);
            } else {

                $patient_id = $request->input('patient_id');
                $illness = $request->input('illness');
                $diagnosis_date = $request->input('diagnosis_date');
                $treatment = $request->input('treatment');

                $data = [
                    'illness' => $illness,
                    'diagnosis_date' => $diagnosis_date,
                    'treatment' => $treatment
                ];

                $appointment = $this->medicalAppointmentRepository->get($appointment_id);
                $status = $appointment->status;

                if (is_null($status) || $status === 'pending') {
                    $this->medicalAppointmentRepository->completeAppointment($patient_id, $appointment_id);
                    $this->medicalHistoryRepository->updateMedicalHistory($patient_id, $appointment_id, $data);

                    $datetime = Carbon::parse($appointment->appointment_date);
                    $appointment->appointment_date = $datetime->toDateString();
                    $appointment->appointment_time = date('H:i', strtotime($datetime->toTimeString()));

                    $this->notificationService->sendAppointmentCompletedMessage($appointment, true);
                    $this->notificationService->sendAppointmentCompletedMessage($appointment, false);

                    return response()->json(['message' => 'Appointment has been completed successfully'], 200);
                } else {
                    return response()->json(['message' => 'Sorry! This appointment has already been marked ' . $appointment->status . '.'], 400);
                }
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function confirmAppointment(Request $request)
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

                $appointment = $this->medicalAppointmentRepository->findAppointmentByNumber($appointment_number);
                $status = $appointment->status;

                if (is_null($status) || $status === 'pending') {
                    $this->medicalAppointmentRepository->confirmAppointment($patient_id, $appointment_number);
                    $datetime = Carbon::parse($appointment->appointment_date);
                    $appointment->appointment_date = $datetime->toDateString();
                    $appointment->appointment_time = date('H:i', strtotime($datetime->toTimeString()));

                    $this->notificationService->sendAppointmentConfirmedMessage($patient_id, $appointment);
                    $this->notificationService->sendDoctorAppointmentConfirmedMessage($appointment->doctor_id, $appointment);

                    return response()->json(['message' => 'Appointment has been confirmed successfully'], 200);
                } else {
                    return response()->json(['message' => 'Sorry! This appointment has already been marked ' . $appointment->status . '.'], 400);
                }
            }
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

                $appointment = $this->medicalAppointmentRepository->findAppointmentByNumber($appointment_number);
                $status = $appointment->status;

                if (is_null($status) || $status === 'pending' || $status === 'confirmed') {
                    $this->medicalAppointmentRepository->cancelAppointment($patient_id, $appointment_number);
                    $datetime = Carbon::parse($appointment->appointment_date);
                    $appointment->appointment_date = $datetime->toDateString();
                    $appointment->appointment_time = date('H:i', strtotime($datetime->toTimeString()));

                    $this->notificationService->sendAppointmentCancelledMessage($patient_id, $appointment);
                    $this->notificationService->sendDoctorAppointmentCancelledMessage($appointment->doctor_id, $appointment);

                    return response()->json(['message' => 'Appointment has been cancelled successfully'], 200);
                } else {
                    return response()->json(['message' => 'Sorry! This appointment has already been marked ' . $appointment->status . '.'], 400);
                }
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }
}
