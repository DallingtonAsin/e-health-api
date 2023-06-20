<?php

namespace App\Repositories\AfterCall\Tests;

use App\Models\OtherTest;

class OtherTestRepository
{
    protected $otherTest;

    public function __construct(OtherTest $otherTest)
    {
        $this->otherTest = $otherTest;
    }

    public function create($otherTestData)
    {
        return $this->otherTest->create($otherTestData);
    }

    public function updateOrCreate($criteria, $data)
    {
        return $this->otherTest->updateOrCreate($criteria, $data);
    }

    public function find($id)
    {
        return $this->otherTest->find($id);
    }

    public function get()
    {
        $otherTests = $this->otherTest->select(['id', 'appointment_id', 'tests', 'findings'])->orderBy('id', 'asc');
        return $otherTests->get();
    }

    public function update($id, $otherTestData)
    {
        $otherTest = $this->otherTest->find($id);
        $otherTest->update($otherTestData);
        return $otherTest;
    }

    public function delete($id)
    {
        $otherTest = $this->otherTest->find($id);
        $otherTest->delete();
        return $otherTest;
    }

    public function exists($id)
    {
        $otherTest = $this->otherTest->where('id', $id)->exists();
        return $otherTest;
    }
}
