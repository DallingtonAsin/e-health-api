<?php

namespace App\Repositories;

use App\Models\Language;

class LanguageRepository
{
    protected $language;

    public function __construct(Language $language)
    {
        $this->language = $language;
    }

    public function create($languageData)
    {
        return $this->language->create($languageData);
    }

    public function get($id = null)
    {

       if($id){
        return $this->language->find($id);
       }

        return $this->language->select(['id', 'name'])->orderBy('id', 'asc')->get();
    }

    public function update($id, $languageData)
    {
        $language = $this->language->find($id);
        $language->update($languageData);
        return $language;
    }

    public function delete($id)
    {
        $language = $this->language->find($id);
        $language->delete();
        return $language;
    }

    public function exists($id)
    {
        $language = $this->language->where('id', $id)->exists();
        return $language;
    }


}
