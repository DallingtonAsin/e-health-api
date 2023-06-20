<?php

namespace App\Repositories\AfterCall\Tests;

use App\Models\ImageTest;

class ImageTestRepository
{
    protected $imageTest;

    public function __construct(ImageTest $imageTest)
    {
        $this->imageTest = $imageTest;
    }

    public function create($imageTestData)
    {
        return $this->imageTest->create($imageTestData);
    }

    public function updateOrCreate($criteria, $data)
    {
        return $this->imageTest->updateOrCreate($criteria, $data);
    }

    public function find($id)
    {
        return $this->imageTest->find($id);
    }

    public function get()
    {
        $imageTests = $this->imageTest->select(['id', 'appointment_id', 'imagetest_category_id', 'findings'])->orderBy('id', 'asc');
        return $imageTests->get();
    }

    public function update($id, $imageTestData)
    {
        $imageTest = $this->imageTest->find($id);
        $imageTest->update($imageTestData);
        return $imageTest;
    }

    public function delete($id)
    {
        $imageTest = $this->imageTest->find($id);
        $imageTest->delete();
        return $imageTest;
    }

    public function exists($id)
    {
        $imageTest = $this->imageTest->where('id', $id)->exists();
        return $imageTest;
    }
}
