<?php

namespace App\Http\Controllers\AfterCall;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\SharedHelper as Helper;
use Illuminate\Support\Facades\Validator;
use App\Repositories\AfterCall\DiagnosisRepository;
use Carbon\Carbon;

class DiagnosisController extends Controller
{

    protected $diagnosisRepository;

    public function __construct(DiagnosisRepository $diagnosisRepository)
    {
        $this->diagnosisRepository = $diagnosisRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return $this->diagnosisRepository->get();
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
            'icd_code_id' => 'required|exists:icd_10_codes,id',
            'comments' => 'required',
            'diagnosis_date' => 'required|date'
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
                    'icd_code_id' => $request->icd_code_id,
                    'comments' => $request->comments,
                    'diagnosis_date' => Carbon::createFromFormat('Y-m-d', $request->diagnosis_date),
                ];

                $result = $this->diagnosisRepository->updateOrCreate($criteria, $validatedData);
                return response()->json(['message' => 'Diagnosis inserted successfully', 'data' => $result], 200);
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
