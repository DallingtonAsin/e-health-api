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

    public function find($id = null)
    {
        return $this->drug->find($id);
    }

    public function get($id = null)
    {

        $drugs = $this->drug->with(['category' => function ($query) {
                   $query->select(['id', 'name']);
        }]);
      

       if($id){
             $drugs->where('id', $id);
       }

        $drugs->orderBy('id', 'asc');
        $drugs = $drugs->get();
  
        $drugs->map(function($drug){
            $drug->price = 'UGX. '.number_format($drug->price);
            $drug->in_stock = $drug->isInStock();
        });

        return $drugs;
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
