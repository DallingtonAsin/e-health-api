<?php

namespace App\Http\Controllers\Doctors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\SharedHelper as Helper;
use Illuminate\Support\Facades\Validator;
use App\Repositories\DoctorAvailabilityRepository;


class DoctorScheduleController extends Controller
{

    protected $doctorAvailabilityRepository;
    public function __construct(DoctorAvailabilityRepository $doctorAvailabilityRepository)
    {
        $this->doctorAvailabilityRepository = $doctorAvailabilityRepository;
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
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:medical_doctors,id',
            'dates' => ['required', 'array'],
            'dates.*' => ['date_format:Y-m-d'],
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time'
        ]);

        try {

            if ($validator->fails()) {
                $message = $validator->errors()->all();
                return Helper::sendFailedHttpResponse($message);
            } else {

                $doctor_id = $request->input('doctor_id');
                $dates = $request->input('dates');
                $start_time = $request->input('start_time');
                $end_time = $request->input('end_time');

                $diffMinutes = (strtotime($end_time) - strtotime($start_time)) / 60;
                if ($diffMinutes < 30) {
                    return Helper::sendFailedHttpResponse("Time difference between start time and end time should be atleast 30 minutes");
                }

                $start_time = date('H:i', strtotime($start_time));
                $end_time = date('H:i', strtotime($end_time));

                foreach ($dates as $date) {
                    $overlapExists = $this->doctorAvailabilityRepository->checkForTimeOverlap($doctor_id, $date, $start_time, $end_time);
                    if ($overlapExists) {
                        return Helper::sendFailedHttpResponse("Time conflict detected on " . $date . " for specified time range " . $start_time . "-" . $end_time . "");
                    }
                }

                $appointmentDates = collect($dates)->map(function ($date) use ($doctor_id, $start_time, $end_time) {
                    return [
                        'doctor_id' => $doctor_id,
                        'date' => $date,
                        'start_time' => $start_time,
                        'end_time' => $end_time
                    ];
                });

                $appointmentDates->each(function ($data) {
                    $criteria = $data;
                    $data['is_deleted'] = false;
                    $this->doctorAvailabilityRepository->updateOrCreateSchedule($criteria, $data);
                });

                return response()->json(['message' => 'Your availability has been updated successfully'], 200);
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
    public function show($doctor_id)
    {
        try {
            $doctor_schedule = $this->doctorAvailabilityRepository->get(null, $doctor_id);
            return response($doctor_schedule, 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function checkDoctorAvailability($doctor_id)
    {
        try {
            $availability = $this->doctorAvailabilityRepository->getDoctorAvailability($doctor_id);
            if ($availability->isEmpty()) {
                return Helper::sendFailedHttpResponse('Doctor cannot be booked as yet.');
            } else {
                return response($availability, 200);
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }


    public function getDoctorAvailabilityWindows($doctor_id)
    {
        try {
            $availabilityWindows = $this->doctorAvailabilityRepository->getDoctorAvailabilityWindows($doctor_id);
            return response($availabilityWindows, 200);
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
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:medical_doctors,id',
            'dates' => ['required', 'array'],
            'dates.*' => ['date_format:Y-m-d'],
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time'
        ]);

        try {

            if ($validator->fails()) {
                $message = $validator->errors()->all();
                return Helper::sendFailedHttpResponse($message);
            } else {

                $doctor_id = $request->input('doctor_id');
                $dates = $request->input('dates');
                $start_time = $request->input('start_time');
                $end_time = $request->input('end_time');

                $diffMinutes = (strtotime($end_time) - strtotime($start_time)) / 60;
                if ($diffMinutes < 30) {
                    return Helper::sendFailedHttpResponse("Time difference between start time and end time should be atleast 30 minutes");
                }

                $start_time = date('H:i', strtotime($start_time));
                $end_time = date('H:i', strtotime($end_time));

                foreach ($dates as $date) {
                    $overlapExists = $this->doctorAvailabilityRepository->checkForTimeOverlapOnUpdate($id, $doctor_id, $date, $start_time, $end_time);
                    if ($overlapExists) {
                        return Helper::sendFailedHttpResponse("Time conflict detected on " . $date . " for specified time range " . $start_time . "-" . $end_time . "");
                    }
                }

                $appointmentDates = collect($dates)->map(function ($date) use ($doctor_id, $start_time, $end_time) {
                    return [
                        'doctor_id' => $doctor_id,
                        'date' => $date,
                        'start_time' => $start_time,
                        'end_time' => $end_time
                    ];
                });

                $appointmentDates->each(function ($data) use ($id) {
                    $this->doctorAvailabilityRepository->update($id, $data);
                });

                return response()->json(['message' => 'Your availability has been updated successfully'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $validator = Validator::make(["id" => $id], [
            'id' => 'required|exists:doctor_availability,id'
        ]);

        try {

            if ($validator->fails()) {
                $message = $validator->errors()->all();
                return Helper::sendFailedHttpResponse($message);
            } else {
                $data = ['is_deleted' => true];
                $res = $this->doctorAvailabilityRepository->update($id, $data);
                $res->makeHidden(['id', 'created_at', 'updated_at']);
                return response()->json(['message' => 'Your schedule has been updated successfully'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
