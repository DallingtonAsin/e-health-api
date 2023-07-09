<?php

namespace App\Repositories\PostCall;

use App\Models\DiagnosisComment;

class DiagnosisCommentsRepository
{
    protected $diagnosisComment;

    public function __construct(DiagnosisComment $diagnosisComment)
    {
        $this->diagnosisComment = $diagnosisComment;
    }

    public function create($diagnosisCommentData)
    {
        return $this->diagnosisComment->create($diagnosisCommentData);
    }

    public function updateOrCreate($criteria, $data)
    {
        return $this->diagnosisComment->updateOrCreate($criteria, $data);
    }

    public function find($id)
    {
        return $this->diagnosisComment->find($id);
    }

    public function get()
    {
        $diagnosisComments = $this->diagnosisComment->select(['id', 'appointment_id', 'comments'])->orderBy('id', 'asc');
        return $diagnosisComments->get();
    }

    public function update($id, $diagnosisCommentData)
    {
        $diagnosisComment = $this->diagnosisComment->find($id);
        $diagnosisComment->update($diagnosisCommentData);
        return $diagnosisComment;
    }

    public function delete($id)
    {
        $diagnosisComment = $this->diagnosisComment->find($id);
        $diagnosisComment->delete();
        return $diagnosisComment;
    }

    public function exists($id)
    {
        $diagnosisComment = $this->diagnosisComment->where('id', $id)->exists();
        return $diagnosisComment;
    }
}
