<?php

namespace App\Repositories;

use App\Models\LabTestCategory;

class LabTestCategoryRepository
{
    protected $labTestCategory;

    public function __construct(LabTestCategory $labTestCategory)
    {
        $this->labTestCategory = $labTestCategory;
    }

    public function create($labTestCategoryData)
    {
        return $this->labTestCategory->create($labTestCategoryData);
    }

    public function find($id )
    {
        return $this->labTestCategory->find($id);
    }

    public function findLabTestCategoryByName($name)
    {
        $labTestCat = $this->labTestCategory->where('name', 'like', '%'.$name.'%')->first();
        return $labTestCat;
        // dd($labTestCat);
    }

    public function get()
    {
        $labTestCategories = $this->labTestCategory->select(['id as key', 'name as value'])->orderBy('id', 'asc');
        return $labTestCategories->get();
    }

    public function update($id, $labTestCategoryData)
    {
        $labTestCategory = $this->labTestCategory->find($id);
        $labTestCategory->update($labTestCategoryData);
        return $labTestCategory;
    }

    public function delete($id)
    {
        $labTestCategory = $this->labTestCategory->find($id);
        $labTestCategory->delete();
        return $labTestCategory;
    }

    public function exists($id)
    {
        $labTestCategory = $this->labTestCategory->where('id', $id)->exists();
        return $labTestCategory;
    }
}
