<?php

namespace App\Repositories\AfterCall\Tests;

use App\Models\LabTest;

class LabTestRepository
{
    protected $labTest;

    public function __construct(LabTest $labTest)
    {
        $this->labTest = $labTest;
    }

    public function create($labTestData)
    {
        return $this->labTest->create($labTestData);
    }

    public function updateOrCreate($criteria, $data)
    {
        return $this->labTest->updateOrCreate($criteria, $data);
    }

    public function find($id)
    {
        return $this->labTest->find($id);
    }

    public function get()
    {
        $labtests = $this->labTest->select(['id', 'appointment_id', 'labtest_category_id', 'findings'])->orderBy('id', 'asc');
        return $labtests->get();
    }

    public function update($id, $labTestData)
    {
        $labTest = $this->labTest->find($id);
        $labTest->update($labTestData);
        return $labTest;
    }

    public function delete($id)
    {
        $labTest = $this->labTest->find($id);
        $labTest->delete();
        return $labTest;
    }

    public function exists($id)
    {
        $labTest = $this->labTest->where('id', $id)->exists();
        return $labTest;
    }
}
