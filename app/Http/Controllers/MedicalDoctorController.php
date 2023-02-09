<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalDoctor;
use App\Repositories\MedicalDoctorRepository;


class MedicalDoctorController extends Controller
{


    protected $medicalDoctorRepository;


    public function __construct(MedicalDoctorRepository $medicalDoctorRepository)
    {
        $this->medicalDoctorRepository = $medicalDoctorRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $medical_doctors = $this->medicalDoctorRepository->get();
            return response($medical_doctors, 200);
        }catch(\Exception $ex){
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getDoctorsBySpecialty($speciality_id)
    {
        try{
            $medical_doctors = $this->medicalDoctorRepository->get(null, $speciality_id);
            return response($medical_doctors, 200);
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
        try{
            $doctor = $this->medicalDoctorRepository->get($id, null);
            return response($doctor[0], 200);
        }catch(\Exception $ex){
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
}
