<?php

namespace App\Http\Controllers\Languages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\LanguageRepository;

class LanguageController extends Controller
{

  protected $languageRepository;

  public function __construct(LanguageRepository $languageRepository)
  {
    $this->languageRepository = $languageRepository;
  }

  public function index()
  {
    try {
      return $this->languageRepository->get();
    } catch (\Exception $ex) {
      return response()->json(['error' => $ex->getMessage()], 500);
    }
  }

  public function getDoctorLanguages()
  {
    try {

      $languages = $this->languageRepository->get();

      foreach ($languages as $language) {
        $language->key =  $language->id;
        $language->value =  $language->name;
        unset($language->id);
        unset($language->name);
      }
      
      return $languages;

    } catch (\Exception $ex) {
      return response()->json(['error' => $ex->getMessage()], 500);
    }
  }
}
