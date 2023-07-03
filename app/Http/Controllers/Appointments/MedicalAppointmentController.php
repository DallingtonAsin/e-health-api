<?php

namespace App\Http\Controllers\Appointments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\SharedHelper as Helper;
use Illuminate\Support\Facades\Validator;
use App\Repositories\MedicalAppointmentRepository;
use App\Repositories\AppointmentTypeRepository;
use App\Repositories\MeetingTokenRepository;
use App\Repositories\MedicalDoctorRepository;
use App\Repositories\PatientMedicalHistoryRepository;
use App\Repositories\AfterCall\MedicalFindingRepository;

use App\Repositories\LabTestCategoryRepository;
use App\Repositories\ImageTestCategoryRepository;
use App\Repositories\Icd10CodeRepository;
use App\Repositories\DrugRepository;
use App\Repositories\AdministrationRouteRepository;

use App\Repositories\AfterCall\MedicalHistoryRepository;
use App\Repositories\AfterCall\Tests\LabTestRepository;
use App\Repositories\AfterCall\Tests\LabTestDocumentRepository;
use App\Repositories\AfterCall\Tests\ImageTestRepository;
use App\Repositories\AfterCall\Tests\OtherTestRepository;
use App\Repositories\AfterCall\DiagnosisRepository;
use App\Repositories\AfterCall\DiagnosisCommentsRepository;
use App\Repositories\AfterCall\PrescriptionRepository;
use App\Repositories\AfterCall\TreatmentPlanRepository;

use App\Services\NotificationService;
use App\Services\PushNotificationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class MedicalAppointmentController extends Controller
{

    protected $appointmentTypeRepository, $medicalAppointmentRepository, $doctorRepository;
    protected $patientMedicalHistoryRepository, $medicalFindingRepository;
    protected $labTestCategoryRepository, $imageTestCategoryRepository, $icd10CodeRepository;

    protected $medicalHistoryRepository, $labTestRepository, $imageTestRepository, $otherTestRepository, $diagnosisRepository, $diagnosisCommentsRepository;
    protected $drugRepository, $adminRouteRepository, $prescriptionRepository, $treatmentPlanRepository, $labTestDocumentRepository;
    protected $meetingTokenRepository, $notificationService, $pushNotificationService;

    public function __construct(
        AppointmentTypeRepository $appointmentTypeRepository,
        MedicalAppointmentRepository $medicalAppointmentRepository,
        MedicalDoctorRepository $doctorRepository,
        MeetingTokenRepository $meetingTokenRepository,
        NotificationService $notificationService,
        MedicalHistoryRepository $medicalHistoryRepository,
        PushNotificationService $pushNotificationService,

        LabTestCategoryRepository $labTestCategoryRepository,
        ImageTestCategoryRepository $imageTestCategoryRepository,
        Icd10CodeRepository $icd10CodeRepository,
        DrugRepository $drugRepository,
        AdministrationRouteRepository $adminRouteRepository,

        LabTestRepository $labTestRepository,
        LabTestDocumentRepository $labTestDocumentRepository,
        ImageTestRepository $imageTestRepository,
        OtherTestRepository $otherTestRepository,

        PatientMedicalHistoryRepository $patientMedicalHistoryRepository,
        MedicalFindingRepository $medicalFindingRepository,
        DiagnosisRepository $diagnosisRepository,
        DiagnosisCommentsRepository $diagnosisCommentsRepository,
        PrescriptionRepository $prescriptionRepository,
        TreatmentPlanRepository $treatmentPlanRepository
    ) {
        $this->appointmentTypeRepository = $appointmentTypeRepository;
        $this->medicalAppointmentRepository = $medicalAppointmentRepository;
        $this->doctorRepository = $doctorRepository;
        $this->meetingTokenRepository = $meetingTokenRepository;
        $this->notificationService = $notificationService;
        $this->pushNotificationService = $pushNotificationService;
        $this->patientMedicalHistoryRepository = $patientMedicalHistoryRepository;
        $this->medicalFindingRepository = $medicalFindingRepository;

        $this->labTestCategoryRepository = $labTestCategoryRepository;
        $this->imageTestCategoryRepository = $imageTestCategoryRepository;
        $this->icd10CodeRepository = $icd10CodeRepository;
        $this->drugRepository = $drugRepository;
        $this->adminRouteRepository = $adminRouteRepository;

        $this->medicalHistoryRepository = $medicalHistoryRepository;
        $this->labTestRepository = $labTestRepository;
        $this->labTestDocumentRepository = $labTestDocumentRepository;
        $this->imageTestRepository = $imageTestRepository;
        $this->otherTestRepository = $otherTestRepository;
        $this->diagnosisRepository = $diagnosisRepository;
        $this->diagnosisCommentsRepository = $diagnosisCommentsRepository;
        $this->prescriptionRepository = $prescriptionRepository;
        $this->treatmentPlanRepository = $treatmentPlanRepository;
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
            return $this->medicalAppointmentRepository->get(null, $patient_id, $status, null);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getPatientPendingAppointments($patient_id)
    {
        try {
            return $this->medicalAppointmentRepository->get(null, $patient_id, 'pending', null);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getPatientConfirmedAppointments($patient_id)
    {
        try {
            return $this->medicalAppointmentRepository->get(null, $patient_id, 'confirmed', null);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getPatientCompletedAppointments($patient_id)
    {
        try {
            return $this->medicalAppointmentRepository->get(null, $patient_id, 'completed', null);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getPatientCancelledAppointments($patient_id)
    {
        try {
            return $this->medicalAppointmentRepository->get(null, $patient_id, 'cancelled', null);
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
            'current_treatment' => 'sometimes|nullable'
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

                $converted_24hr_time = Carbon::createFromFormat('h:i A', $time)->format('H:i');
                $appointment_type = $this->appointmentTypeRepository->getAppointmentTypeByName($appointment_type_name);
                $appointment_date =  Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $converted_24hr_time);
                $appointment_type_id = $appointment_type->id;
                $exists = $this->medicalAppointmentRepository->checkIfAppointmentExists($patient_id, $doctor_id, $appointment_type_id, $appointment_date);

                $isConflict = $this->medicalAppointmentRepository->isAppointmentConflict($doctor_id, $appointment_date);
                if ($appointment_date->isPast()) {
                    return response()->json(['message' => 'The selected appointment time has already passed. Please choose a different time slot.'], 400);
                } else if ($isConflict) {
                    return response()->json(['message' => 'Another appointment is already booked at the selected time. Please choose a different time slot.'], 400);
                } else {
                    if ($exists) {
                        return response(['error' => 'You have already booked an appointment with such details.'], 400);
                    } else {

                        $appointment_number = $this->medicalAppointmentRepository->generateAppointmentNumber();
                        $doctor = $this->doctorRepository->find($doctor_id);

                        $data = [
                            'patient_id' => $patient_id,
                            'doctor_id' => $doctor_id,
                            'appointment_number' => $appointment_number,
                            'appointment_type_id' => $appointment_type->id,
                            'appointment_date' => $appointment_date,
                            'reason' => $reason,
                        ];

                        if ($doctor->auto_approve) {
                            $data['status'] = 'confirmed';
                            $data['confirmed_at'] = Carbon::now();
                        }
                        $data = $this->medicalAppointmentRepository->create($data);
                        $data->makeHidden(['created_at', 'updated_at']);

                        $criteria = [
                            'patient_id' => $patient_id,
                            'appointment_id' => $data->id,
                        ];

                        $medical_history_data = [
                            'patient_id' => $patient_id,
                            'appointment_id' => $data->id,
                            'past_medical_history' => $past_medical_history,
                            'current_treatment' => $current_treatment
                        ];

                        $this->patientMedicalHistoryRepository->updateOrCreate($criteria, $medical_history_data);

                        $appointment = $this->medicalAppointmentRepository->find($data->id);
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

                        if ($doctor->auto_approve) {
                            $this->notificationService->sendAppointmentConfirmedMessage($patient_id, $data);
                            $this->notificationService->sendDoctorAppointmentConfirmedMessage($appointment->doctor_id, $data);
                        }

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
            $notification = $this->medicalAppointmentRepository->get($id, null, null, null);
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
            return $this->medicalAppointmentRepository->get(null, null, 'pending', $doctor_id);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getDoctorConfirmedAppointments($doctor_id)
    {
        try {
            return $this->medicalAppointmentRepository->get(null, null, 'confirmed', $doctor_id);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getDoctorCompletedAppointments($doctor_id)
    {
        try {
            return $this->medicalAppointmentRepository->get(null, null, 'completed', $doctor_id);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getDoctorCancelledAppointments($doctor_id)
    {
        try {
            return $this->medicalAppointmentRepository->get(null, null, 'cancelled', $doctor_id);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }


    public function getAppointmentMeetingDetails($appointment_id)
    {
        try {
            $meeting = $this->medicalAppointmentRepository->get($appointment_id);
            return $meeting[0];
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function completeAppointment(Request $request, $appointment_id)
    {


        $payload = json_decode($request->payload, true);
        $validator = Validator::make($payload, [
            'appointmentId' => 'required|exists:medical_appointments,id',
            'isDraft' => 'required|boolean',
            'historyData' => 'required|array',
            'historyData.presenting_complaint' => 'required',
            'historyData.past_medical_history' => 'required',
            'historyData.drug_allergies' => 'required',
            'historyData.findings' => 'required',

            'labTestData' => 'sometimes|required|array',
            'labTestData.labTests' => [
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    if (!empty($value) && !is_array($value)) {
                        $fail('The labTests must be an array.');
                    }
                },
            ],
            'labTestData.labTests.*.name' => 'required|string',
            'labTestData.labTests.*.findings' => 'required|string',

            'labTestData.imageTests' => [
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    if (!empty($value) && !is_array($value)) {
                        $fail('The imageTests must be an array.');
                    }
                },
            ],
            'labTestData.imageTests.*.name' => 'required|string',
            'labTestData.imageTests.*.findings' => 'required|string',

            'labTestData.otherTests' => 'nullable|string',
            'labTestData.otherTestFindings' => 'nullable|string',

            'diagnosisData' => 'nullable|array',
            'diagnosisData.icd10Codes.*' => 'required|string',
            'diagnosisData.icd10Codes' => 'sometimes|nullable|array',
            'diagnosisData.comments' => 'required|string',

            'treatmentData' => 'nullable|array',
            'treatmentData.drugs' => 'sometimes|nullable|array',
            'treatmentData.drugs.*.name' => 'required|string',
            'treatmentData.drugs.*.dosage' => 'required|string',
            'treatmentData.drugs.*.duration' => 'required|integer',
            'treatmentData.drugs.*.instructions' => 'required|string',
            'treatmentData.drugs.*.quantity' => 'required|integer',
            'treatmentData.drugs.*.route_of_admin' => 'required|string',
            'treatmentData.treatmentPlan' => 'required|string'

        ]);

        $fileValidator = Validator::make($request->all(), [
            'labTestDocuments' => 'sometimes|array',
            'labTestDocuments.*' => 'file|mimes:pdf,jpg,png|max:2048'
        ]);

        try {

            if ($validator->fails() || $fileValidator->fails()) {
                $message = $validator->errors()->all();
                return Helper::sendFailedHttpResponse($message);
            } else {

                $appointmentData = $this->medicalAppointmentRepository->get($appointment_id);
                $appointment = $appointmentData[0];

                if ($appointment->is_draft) {

                    $appointment_id = $payload['appointmentId'];
                    $isDraft = $payload['isDraft'];
                    $criteria = [
                        'appointment_id' => $appointment_id
                    ];

                    // Process history data
                    $historyData = $payload['historyData'];
                    $presenting_complaint = $historyData['presenting_complaint'];
                    $past_medical_history = $historyData['past_medical_history'];
                    $drug_allergies = $historyData['drug_allergies'];
                    $findings = $historyData['findings'];

                    $medicalHistoryData = [
                        'appointment_id' => $appointment_id,
                        'presenting_complaint' => $presenting_complaint,
                        'past_medical_history' => $past_medical_history,
                        'drug_allergies' => $drug_allergies,
                        'findings' => $findings
                    ];
                    $this->medicalHistoryRepository->updateOrCreate($criteria, $medicalHistoryData);


                    // Process Labtest data
                    $labTestData = $payload['labTestData'];

                    if (isset($labTestData)) {
                        $otherTests = $labTestData['otherTests'];
                        $otherTestFindings = $labTestData['otherTestFindings'];

                        // Perform necessary operations with the validated Labtest data
                        if (!empty($labTestData['labTests'])) {
                            $labTests = $labTestData['labTests'];
                            foreach ($labTests as $labTest) {
                                $labTestName = $labTest['name'];
                                $labTestCategoryId = $this->labTestCategoryRepository->findLabTestCategoryByName($labTestName)->id;
                                $labCriteria = [
                                    'appointment_id' => $appointment_id,
                                    'labtest_category_id' => $labTestCategoryId
                                ];
                                $labTestObj =  [
                                    'appointment_id' => $appointment_id,
                                    'labtest_category_id' => $labTestCategoryId,
                                    'findings' => $labTest['findings'],
                                ];
                                $this->labTestRepository->updateOrCreate($labCriteria, $labTestObj);
                            }
                        }

                        // Perform necessary operations with the validated imagetest data
                        if (!empty($labTestData['imageTests'])) {
                            $imageTests = $labTestData['imageTests'];
                            foreach ($imageTests as $imageTest) {
                                $imageTestCat = $this->imageTestCategoryRepository->findImageTestCategoryByName($imageTest['name']);
                                $imageTestCatId = $imageTestCat->id;
                                $imageTestCriteria = [
                                    'appointment_id' => $appointment_id,
                                    'imagetest_category_id' => $imageTestCatId
                                ];
                                $imageTestObj = [
                                    'appointment_id' => $appointment_id,
                                    'imagetest_category_id' => $imageTestCatId,
                                    'findings' => $imageTest['findings'],
                                ];
                                $this->imageTestRepository->updateOrCreate($imageTestCriteria, $imageTestObj);
                            }
                        }

                        // Perform necessary operations with the validated othertests data
                        if (!empty($otherTests) && !empty($otherTestFindings)) {
                            $otherTests = [
                                'appointment_id' => $appointment_id,
                                'tests' => $otherTests,
                                'findings' => $otherTestFindings,
                            ];
                            $this->otherTestRepository->updateOrCreate($criteria, $otherTests);
                        }
                    }

                    // Diagnosis data
                    $diagnosisData = $payload['diagnosisData'];
                    if (isset($diagnosisData)) {
                        $icd10Codes = $diagnosisData['icd10Codes'];
                        $diagnosis_comments = $diagnosisData['comments'];

                        if (!empty($icd10Codes)) {
                            // Insert each icd10Code into the database
                            foreach ($icd10Codes as $icdString) {
                                $parts = explode(' ', $icdString);
                                $categoryCode = $parts[0];
                                $abbrev = implode(' ', array_slice($parts, 1));
                                // dd($categoryCode, $abbrev);
                                $icd10Code = $this->icd10CodeRepository->findIcd10CodeByCodeAbbrev($categoryCode, $abbrev);
                                $icd10CodeId = $icd10Code->id;

                                $diagnosisCriteria = [
                                    'appointment_id' => $appointment_id,
                                    'icd_code_id' => $icd10CodeId
                                ];

                                $diagnosisData = [
                                    'appointment_id' => $appointment_id,
                                    'icd_code_id' => $icd10CodeId,
                                    'diagnosis_date' => Carbon::now(),
                                ];
                                $this->diagnosisRepository->updateOrCreate($diagnosisCriteria, $diagnosisData);
                            }
                        }

                        if (!empty($diagnosis_comments)) {
                            $diagnosisCommentData = [
                                'appointment_id' => $appointment_id,
                                'comments' => $diagnosis_comments
                            ];
                            $this->diagnosisCommentsRepository->updateOrCreate($criteria, $diagnosisCommentData);
                        }
                    }

                    // Treatment data
                    $treatmentData = $payload['treatmentData'];
                    if (isset($treatmentData)) {
                        $drugs = $treatmentData['drugs'];
                        $treatmentPlan = $treatmentData['treatmentPlan'];

                        if (!empty($drugs)) {

                            foreach ($drugs as $drug) {

                                $drugName = $drug['name'];
                                $dosage = $drug['dosage'];
                                $duration = $drug['duration'];
                                $quantity = $drug['quantity'];
                                $routeOfAdmin = $drug['route_of_admin'];
                                $instructions = $drug['instructions'];
                                $drug = $this->drugRepository->findDrugByName($drugName);
                                $drug_id = $drug->id;

                                $drugObj = [
                                    'appointment_id' => $appointment_id,
                                    'drug_id' => $drug_id,
                                    'dosage' => $dosage,
                                    'admin_route_id' => $this->adminRouteRepository->findRouteByName($routeOfAdmin)->id,
                                    'duration' => $duration,
                                    'quantity' => $quantity,
                                    'instructions' => $instructions,
                                ];

                                // Perform necessary operations with each drug
                                $this->prescriptionRepository->updateOrCreate(['appointment_id' => $appointment_id,  'drug_id' => $drug_id], $drugObj);
                            }
                        }

                        if (!empty($treatmentPlan)) {
                            $treatmentManagmentData = [
                                'appointment_id' => $appointment_id,
                                'treatment_plan' => $treatmentPlan
                            ];
                            $this->treatmentPlanRepository->updateOrCreate($criteria, $treatmentManagmentData);
                        }
                    }

                    // upload labtest documents 
                    if ($request->hasFile('labTestDocuments')) {
                        foreach ($request->file('labTestDocuments') as $index => $file) {
                            $file_extension = $file->getClientOriginalExtension();
                            $file_name = $appointment->appointment_number . '_' . $index . '.' . $file_extension;

                            $doesFileExist = $this->labTestDocumentRepository->doesFileExist($appointment_id, $file_name);
                            if ($doesFileExist) {
                                if (Storage::disk('public')->exists($file_name)) {
                                    Storage::disk('public')->delete($file_name);
                                    $this->labTestDocumentRepository->deleteFile($appointment_id, $file_name);
                                }
                            }
                            $file_path = $file->storeAs('documents/labtests', $file_name, 'public');
                            $is_image = in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif']);

                            $fileData = [
                                'appointment_id' => $appointment_id,
                                'file_path' => $file_path,
                                'type' => $file_extension,
                                'is_image' => $is_image
                            ];
                            $this->labTestDocumentRepository->create($fileData);
                        }
                    }

                    // if not draft, complete the appointment
                    if (!$isDraft) {
                        $this->medicalAppointmentRepository->update($appointment_id, ['is_draft' => false]);
                        $patient_id = $appointment->patient_id;
                        $this->medicalAppointmentRepository->completeAppointment($patient_id, $appointment_id);
                        $datetime = Carbon::parse($appointment->appointment_date);
                        $appointment->appointment_date = $datetime->toDateString();
                        $appointment->appointment_time = date('H:i', strtotime($datetime->toTimeString()));

                        $this->notificationService->sendAppointmentCompletedMessage($appointment, true);
                        $this->notificationService->sendAppointmentCompletedMessage($appointment, false);
                    }

                    $message = $isDraft ? 'Data saved as draft' : 'Appointment has been completed successfully';
                    return response()->json(['message' => $message], 200);
                } else {
                    return Helper::sendFailedHttpResponse('You have already been completed this appointment.');
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
                    $this->markAppointmentConfirmed($appointment, $appointment_number, $patient_id);
                    return response()->json(['message' => 'Appointment has been confirmed successfully'], 200);
                } else {
                    return response()->json(['message' => 'Sorry! This appointment has already been marked ' . $appointment->status . '.'], 400);
                }
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    private function markAppointmentConfirmed($appointment, $appointment_number, $patient_id)
    {
        try {
            $this->medicalAppointmentRepository->confirmAppointment($patient_id, $appointment_number);
            $datetime = Carbon::parse($appointment->appointment_date);
            $appointment->appointment_date = $datetime->toDateString();
            $appointment->appointment_time = date('H:i', strtotime($datetime->toTimeString()));

            $this->notificationService->sendAppointmentConfirmedMessage($patient_id, $appointment);
            $this->notificationService->sendDoctorAppointmentConfirmedMessage($appointment->doctor_id, $appointment);
        } catch (\Exception $ex) {
            throw $ex;
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

    public function checkAppointmentStatus($appointmentId)
    {
        try {
            $exists = $this->medicalAppointmentRepository->exists($appointmentId);
            if ($exists) {
                $isExpired = $this->medicalAppointmentRepository->isAppointmentExpired($appointmentId);
                if ($isExpired) {
                    return response()->json(['message' => 'This appointment has already expired'], 422);
                } else {
                    $appointment = $this->medicalAppointmentRepository->get($appointmentId);
                    return response()->json($appointment, 200);
                }
            } else {
                return response()->json(['message' => 'Invalid request'], 400);
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getAppointmentConsultationData($appointment_id)
    {
        try {
            $data = $this->medicalAppointmentRepository->getPostConsulationData($appointment_id);
            return response()->json($data, 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }
}
