<?php

namespace App\Http\Controllers\AfterCall;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\AfterCall\MedicalHistoryRepository;
use App\Repositories\MedicalHistory\HeldCallRepository;
use App\Repositories\MedicalAppointmentRepository;

class MedicalHistoryController extends Controller
{

    protected $medicalHistoryRepository, $heldCallRepository, $medicalAppointmentRepository;

    public function __construct(
        MedicalHistoryRepository $medicalHistoryRepository,
        HeldCallRepository $heldCallRepository,
        MedicalAppointmentRepository $medicalAppointmentRepository
    ) {
        $this->medicalHistoryRepository = $medicalHistoryRepository;
        $this->heldCallRepository = $heldCallRepository;
        $this->medicalAppointmentRepository = $medicalAppointmentRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return $this->medicalHistoryRepository->get();
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

    public function getPatientHeldAppointments()
    {
        try {
            $patient_id = auth('patient')->user()->id;
            return $this->medicalAppointmentRepository->getHeldAppointments(null, $patient_id);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getDoctorHeldAppointments()
    {
        try {
            $doctor_id = auth('doctor')->user()->id;
            return $this->medicalAppointmentRepository->getHeldAppointments(null, null, $doctor_id);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getPatientLabTests()
    {
        try {
            $patient_id = auth('patient')->user()->id;
            return $this->medicalAppointmentRepository->getHeldTests(null, $patient_id);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getDoctorLabTests()
    {
        try {
            $doctor_id = auth('doctor')->user()->id;
            return $this->medicalAppointmentRepository->getHeldTests(null, null, $doctor_id);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getPatientDiagnosis()
    {
        try {
            $patient_id = auth('patient')->user()->id;
            return $this->medicalAppointmentRepository->getConductedDiagnosis(null, $patient_id);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getDoctorDiagnois()
    {
        try {
            $doctor_id = auth('doctor')->user()->id;
            return $this->medicalAppointmentRepository->getConductedDiagnosis(null, null, $doctor_id);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getPatientTreatments()
    {
        try {
            $patient_id = auth('patient')->user()->id;
            return $this->medicalAppointmentRepository->getConductedTreatments(null, $patient_id);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getDoctorTreatments()
    {
        try {
            $doctor_id = auth('doctor')->user()->id;
            return $this->medicalAppointmentRepository->getConductedTreatments(null, null, $doctor_id);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }
}
