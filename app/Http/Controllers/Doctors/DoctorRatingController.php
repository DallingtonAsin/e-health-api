<?php

namespace App\Http\Controllers\Doctors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\SharedHelper as Helper;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Doctor\DoctorRatingRepository;


class DoctorRatingController extends Controller
{
    protected $doctorRatingRepository;

    public function __construct(DoctorRatingRepository $doctorRatingRepository)
    {
        $this->doctorRatingRepository = $doctorRatingRepository;
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


    public function postRating(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:medical_doctors,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'sometimes|nullable'
        ]);

        try {
            if ($validator->fails()) {
                $message = $validator->errors()->all();
                return Helper::sendFailedHttpResponse($message);
            } else {
                $doctor_id = $request->input('doctor_id');
                $patient_id = auth('patient')->user()->id;
                $rating = $request->input('rating');
                $comment = $request->input('comment');
                $doctorRating = $this->doctorRatingRepository->postRating($doctor_id, $patient_id, $rating, $comment);

                return response()->json(['message' => 'Rating submitted successfully', 'data' => $doctorRating], 200);
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
