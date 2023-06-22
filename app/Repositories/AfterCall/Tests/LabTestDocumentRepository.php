<?php

namespace App\Repositories\AfterCall\Tests;

use App\Models\LabtestDocument;

class LabTestDocumentRepository
{
    protected $labTestDocument;

    public function __construct(LabtestDocument $labTestDocument)
    {
        $this->labTestDocument = $labTestDocument;
    }

    public function create($labTestDocumentData)
    {
        return $this->labTestDocument->create($labTestDocumentData);
    }

    public function updateOrCreate($criteria, $data)
    {
        return $this->labTestDocument->updateOrCreate($criteria, $data);
    }

    public function find($id)
    {
        return $this->labTestDocument->find($id);
    }

    public function get()
    {
        $labTestDocuments = $this->labTestDocument->select(['id', 'appointment_id', 'file_path'])->orderBy('id', 'asc');
        return $labTestDocuments->get();
    }

    public function update($id, $labTestDocumentData)
    {
        $labTestDocument = $this->labTestDocument->find($id);
        $labTestDocument->update($labTestDocumentData);
        return $labTestDocument;
    }

    public function delete($id)
    {
        $labTestDocument = $this->labTestDocument->find($id);
        $labTestDocument->delete();
        return $labTestDocument;
    }

    public function exists($id)
    {
        $labTestDocument = $this->labTestDocument->where('id', $id)->exists();
        return $labTestDocument;
    }

    public function doesFileExist($appointmentId, $filePath)
    {
        $exists = $this->labTestDocument->where('appointment_id', $appointmentId)
            ->where('file_path', $filePath)->exists();
        return $exists;
    }


    public function deleteFile($appointmentId, $filePath)
    {
        $this->labTestDocument->where('appointment_id', $appointmentId)->where('file_path', $filePath)->delete();
    }
}
