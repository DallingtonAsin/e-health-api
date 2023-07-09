<?php

namespace App\Http\Controllers\PostCall;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\SharedHelper as Helper;
use Illuminate\Support\Facades\Validator;
use App\Repositories\PostCall\MedicalFindingRepository;

class MedicalFindingController extends Controller
{

    protected $medicalFindingRepository;

    public function __construct(MedicalFindingRepository $medicalFindingRepository)
    {
        $this->medicalFindingRepository = $medicalFindingRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return $this->medicalFindingRepository->get();
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
            'labtest_category_id' => 'required|exists:labtest_categories,id',
            'finding' => 'required'
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
                    'labtest_category_id' => $request->labtest_category_id,
                    'finding' => $request->finding
                ];

                $result = $this->medicalFindingRepository->updateOrCreate($criteria, $validatedData);
                return response()->json(['message' => 'Medical findings inserted successfully', 'data' => $result], 200);
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
