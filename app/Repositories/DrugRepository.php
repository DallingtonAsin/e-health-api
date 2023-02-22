<?php

namespace App\Repositories;

use App\Models\Drug;

class DrugRepository
{
    protected $drug;

    public function __construct(Drug $drug)
    {
        $this->drug = $drug;
    }

    public function create($drugData)
    {
        return $this->drug->create($drugData);
    }

    public function get($id = null)
    {

       if($id){
        return $this->drug->find($id);
       }

        return $this->drug->select(['id', 'name', 'description', 'price', 'image', 'status'])->orderBy('id', 'asc')->get();
    }

    public function update($id, $drugData)
    {
        $drug = $this->drug->find($id);
        $drug->update($drugData);
        return $drug;
    }

    public function delete($id)
    {
        $drug = $this->drug->find($id);
        $drug->delete();
        return $drug;
    }

    public function exists($id)
    {
        $drug = $this->drug->where('id', $id)->exists();
        return $drug;
    }


}
