<?php

namespace App\Repositories;

use App\Models\DoctorRating;

class DoctorRatingRepository
{
    protected $rating;

    public function __construct(DoctorRating $rating)
    {
        $this->rating = $rating;
    }

    public function create($data)
    {
        return $this->rating->create($data);
    }

    public function postRating($doctor_id, $patient_id, $rating, $comment =  null)
    {
        $doctorRating = $this->rating->where('doctor_id', $doctor_id)
            ->where('patient_id', $patient_id)
            ->first();

        if ($doctorRating) {
            $comment = $doctorRating->comment ? $doctorRating->comment : $comment;
            $doctorRating->rating = $rating;
            $doctorRating->comment = $comment;
            $doctorRating->save();
        } else {
            $data = [
                'doctor_id' => $doctor_id,
                'patient_id' => $patient_id,
                'rating' => $rating,
                'comment' => $comment,
            ];
            $doctorRating = $this->create($data);
        }
        return $doctorRating;
    }

    public function find($id)
    {
        return $this->rating->find($id);
    }

    public function get($id)
    {
        return $this->rating->get();
    }

    public function update($id, $ratingData)
    {
        $rating = $this->rating->find($id);
        $rating->update($ratingData);
        return $rating;
    }

    public function delete($id)
    {
        $rating = $this->rating->find($id);
        $rating->delete();
        return $rating;
    }

    public function exists($id)
    {
        $rating = $this->rating->where('id', $id)->exists();
        return $rating;
    }
}
