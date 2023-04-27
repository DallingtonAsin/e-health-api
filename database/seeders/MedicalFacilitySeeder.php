<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MedicalFacility;

class MedicalFacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MedicalFacility::create(['district' => 'Kampala', 'name' => 'Mulago Hospital']);
        MedicalFacility::create(['district' => 'Kampala', 'name' => 'Kirundu Hospital']);
        MedicalFacility::create(['district' => 'Mbarara', 'name' => 'Mbarara Referral']);
        MedicalFacility::create(['district' => 'Kampala', 'name' => 'Kawempe Hospital']);
        MedicalFacility::create(['district' => 'Kampala', 'name' => 'Nakesero Hospital']);
        MedicalFacility::create(['district' => 'Kampala', 'name' => 'Makerere University Hospital']);
    }
}
