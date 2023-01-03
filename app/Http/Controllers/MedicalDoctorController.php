<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalDoctor;
use Mockery\Exception;

class MedicalDoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $medical_doctors = MedicalDoctor::all();
            $medical_doctors->makeHidden(['created_at', 'updated_at']);
            foreach($medical_doctors as $doctor){
                $doctor->languages = unserialize(($doctor->languages));
            }
            return response($medical_doctors, 200);
        }catch(Exception $ex){
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getDoctorsBySpecialty($speciality_id)
    {
        try{
            $medical_doctors = MedicalDoctor::where('specialty_id', $speciality_id)->get();
            $medical_doctors->makeHidden(['created_at', 'updated_at']);
            foreach($medical_doctors as $doctor){
                $doctor->languages = implode(", ", unserialize(($doctor->languages)));
                $doctor->service_fee = config('app.currency') . '. ' . number_format($doctor->service_fee);
            }
            return response($medical_doctors, 200);
        }catch(Exception $ex){
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
            $doctor_info = MedicalDoctor::where('id', $id)->get();
            $doctor_info->makeHidden(['created_at', 'updated_at']);
            foreach($doctor_info as $info){
                $info->languages = implode(", ", unserialize(($info->languages)));
                $info->service_fee = config('app.currency') . '. ' . number_format($info->service_fee);
            }
            return response($doctor_info[0], 200);
        }catch(Exception $ex){
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
