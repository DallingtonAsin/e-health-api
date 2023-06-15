<?php

namespace App\Repositories;

use App\Models\ICDCode;

class Icd10CodeRepository
{
    protected $icd10Code;

    public function __construct(ICDCode $icd10Code)
    {
        $this->icd10Code = $icd10Code;
    }

    public function create($icd10CodeData)
    {
        return $this->icd10Code->create($icd10CodeData);
    }

    public function find($id = null)
    {
        return $this->icd10Code->find($id);
    }

    public function get($id = null)
    {
        $icd10Codes = $this->icd10Code->select(['id', 'category_code', 'abbreviated_description']);
        if ($id) {
            $icd10Codes->where('id', $id);
        }
        $icd10Codes->orderBy('id', 'asc');
        $icd10Codes = $icd10Codes->limit(200)->get();

        $icd10Codes->map(function ($icd10Code) {
            $icd10Code->name = trim($icd10Code->category_code) . ' ' . trim($icd10Code->abbreviated_description);
            unset($icd10Code->category_code);
            unset($icd10Code->abbreviated_description);
        });

        return $icd10Codes;
    }

    public function update($id, $icd10CodeData)
    {
        $icd10Code = $this->icd10Code->find($id);
        $icd10Code->update($icd10CodeData);
        return $icd10Code;
    }

    public function delete($id)
    {
        $icd10Code = $this->icd10Code->find($id);
        $icd10Code->delete();
        return $icd10Code;
    }

    public function exists($id)
    {
        $icd10Code = $this->icd10Code->where('id', $id)->exists();
        return $icd10Code;
    }
}
