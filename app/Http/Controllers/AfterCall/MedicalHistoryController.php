<?php

namespace App\Http\Controllers\AfterCall;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\SharedHelper as Helper;
use Illuminate\Support\Facades\Validator;
use App\Repositories\AfterCall\MedicalHistoryRepository;

class MedicalHistoryController extends Controller
{

    protected $medicalHistoryRepository;

    public function __construct(MedicalHistoryRepository $medicalHistoryRepository)
    {
        $this->medicalHistoryRepository = $medicalHistoryRepository;
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
        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|exists:medical_appointments,id',
            'medical_history' => 'required',
            'drug_allergies' => 'required'
        ]);

        try {
            if ($validator->fails()) {
                $message = $validator->errors()->all();
                return Helper::sendFailedHttpResponse($message);
            } else {

                $criteria = [
                    'appointment_id' => $request->appointment_id
                ];
                $validatedData = [
                    'appointment_id' => $request->appointment_id,
                    'medical_history' => $request->medical_history,
                    'drug_allergies' => $request->drug_allergies
                ];

                $result = $this->medicalHistoryRepository->createOrUpdate($criteria, $validatedData);
                return response()->json(['message' => 'Patient medical history inserted successfully', 'data' => $result], 200);
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
