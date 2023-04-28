<?php

namespace App\Repositories;

use App\Models\DoctorIdentificationDocument;
use Illuminate\Support\Facades\Storage;

class DoctorIdentificationRepository
{
    protected $doctorIdentification;

    public function __construct(DoctorIdentificationDocument $doctorIdentification)
    {
        $this->doctorIdentification = $doctorIdentification;
    }

    public function create($doctorIdentificationData)
    {
        return $this->doctorIdentification->create($doctorIdentificationData);
    }

    public function find($id)
    {
        return $this->doctorIdentification->find($id);
    }

    public function get()
    {
        return $this->doctorIdentification->all();
    }

    public function update($id, $doctorIdentificationData)
    {
        $doctorIdentification = $this->doctorIdentification->find($id);
        $doctorIdentification->update($doctorIdentificationData);
        return $doctorIdentification;
    }

    public function updateByDoctorId($doctor_id, $doctorIdentificationData)
    {
        $doctorIdentification = $this->doctorIdentification->where('doctor_id', $doctor_id);
        $doctorIdentification->update($doctorIdentificationData);
        return $doctorIdentification->first();
    }

    public function delete($id)
    {
        $doctorIdentification = $this->doctorIdentification->find($id);
        $doctorIdentification->delete();
        return $doctorIdentification;
    }

    public function exists($id)
    {
        $doctorIdentification = $this->doctorIdentification->where('id', $id)->exists();
        return $doctorIdentification;
    }

    public function checkIfDoctorDocsExist($doctor_id)
    {
        $exists = $this->doctorIdentification->where('doctor_id', $doctor_id)->exists();
        return $exists;
    }

    public function saveIdentificationFrontFile($doctor_id, $file, $file_extension)
    {
        try {
            $doctor_identification = $this->doctorIdentification->where('doctor_id', $doctor_id);
            if ($doctor_identification->exists()) {
                $identification = $doctor_identification->first();
                if (!is_null($identification->front)) {
                    if (Storage::disk('public')->exists($identification->front)) {
                        Storage::disk('public')->delete($identification->front);
                    }
                }
            }
            $filename = 'font_'. $doctor_id . '' . time() . '.' . $file_extension;
            $filePath = $file->storeAs('images/doctors/identification', $filename, 'public');

            return $filePath;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function saveIdentificationBackFile($doctor_id, $file, $file_extension)
    {
        try {
            $doctor_identification = $this->doctorIdentification->where('doctor_id', $doctor_id);
            if ($doctor_identification->exists()) {
                $identification = $doctor_identification->first();
                if (!is_null($identification->back)) {
                    if (Storage::disk('public')->exists($identification->back)) {
                        Storage::disk('public')->delete($identification->back);
                    }
                }
            }
            $filename = 'back_'.$doctor_id . '' . time() . '.' . $file_extension;
            $filePath = $file->storeAs('images/doctors/identification', $filename, 'public');

            return $filePath;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
