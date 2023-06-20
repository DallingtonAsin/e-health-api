<?php

namespace App\Repositories;

use App\Models\MedicalAdministrationRoute;

class AdministrationRouteRepository
{
    protected $administrationRoute;

    public function __construct(MedicalAdministrationRoute $administrationRoute)
    {
        $this->administrationRoute = $administrationRoute;
    }

    public function create($data)
    {
        return $this->administrationRoute->create($data);
    }

    public function find($id)
    {
        return $this->administrationRoute->find($id);
    }

    public function findRouteByName($name)
    {
        return $this->administrationRoute->where('name', $name)->first();
    }

    public function get()
    {
        $labTestCategories = $this->administrationRoute->select(['id as key', 'name as value'])->orderBy('id', 'asc');
        return $labTestCategories->get();
    }

    public function update($id, $labTestCategoryData)
    {
        $administrationRoute = $this->administrationRoute->find($id);
        $administrationRoute->update($labTestCategoryData);
        return $administrationRoute;
    }

    public function delete($id)
    {
        $administrationRoute = $this->administrationRoute->find($id);
        $administrationRoute->delete();
        return $administrationRoute;
    }

    public function exists($id)
    {
        $administrationRoute = $this->administrationRoute->where('id', $id)->exists();
        return $administrationRoute;
    }
}
