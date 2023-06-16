<?php

namespace Database\Seeders;

use App\Models\MedicalAdministrationRoute;
use Illuminate\Database\Seeder;

class MedicalAdministrationRouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MedicalAdministrationRoute::create(['name' => 'Oral']);
        MedicalAdministrationRoute::create(['name' => 'Sublingual']);
        MedicalAdministrationRoute::create(['name' => 'Intranasal']);
        MedicalAdministrationRoute::create(['name' => 'Rectal']);
        MedicalAdministrationRoute::create(['name' => 'Intramuscular']);
        MedicalAdministrationRoute::create(['name' => 'IV Push']);
        MedicalAdministrationRoute::create(['name' => 'IV Infusion']);
        MedicalAdministrationRoute::create(['name' => 'Rectal']);
        MedicalAdministrationRoute::create(['name' => 'Subcutaneous']);
        MedicalAdministrationRoute::create(['name' => 'Topical']);
        MedicalAdministrationRoute::create(['name' => 'Intraarticular']);
        MedicalAdministrationRoute::create(['name' => 'Intraocular']);
        MedicalAdministrationRoute::create(['name' => 'Vaginal']);
        MedicalAdministrationRoute::create(['name' => 'Nebulisation']);
        MedicalAdministrationRoute::create(['name' => 'Intraosseous']);

    }
}
