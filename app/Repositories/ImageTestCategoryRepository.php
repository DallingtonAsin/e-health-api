<?php

namespace App\Repositories;

use App\Models\ImageTestCategory;

class ImageTestCategoryRepository
{
    protected $imageTestCategory;

    public function __construct(ImageTestCategory $imageTestCategory)
    {
        $this->imageTestCategory = $imageTestCategory;
    }

    public function create($imageTestCategoryData)
    {
        return $this->imageTestCategory->create($imageTestCategoryData);
    }

    public function find($id)
    {
        return $this->imageTestCategory->find($id);
    }

    public function findImageTestCategoryByName($name)
    {
        return $this->imageTestCategory->where('name', 'LIKE', '%' . $name . '%')->first();
    }

    public function get()
    {
        $imageTestCategories = $this->imageTestCategory->select(['id as key', 'name as value'])->orderBy('id', 'asc');
        $imageTestCategories = $imageTestCategories->get();
        $imageTestCategories->map(function ($category) {
            $category->value = trim($category->value);
        });
        return $imageTestCategories;
    }

    public function update($id, $imageTestCategoryData)
    {
        $imageTestCategory = $this->imageTestCategory->find($id);
        $imageTestCategory->update($imageTestCategoryData);
        return $imageTestCategory;
    }

    public function delete($id)
    {
        $imageTestCategory = $this->imageTestCategory->find($id);
        $imageTestCategory->delete();
        return $imageTestCategory;
    }

    public function exists($id)
    {
        $imageTestCategory = $this->imageTestCategory->where('id', $id)->exists();
        return $imageTestCategory;
    }
}
