<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DrugCategory;

class DrugCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DrugCategory::create(['name' => 'Herbal Medicine']);
        DrugCategory::create(['name' => 'Home Medecines']);
        DrugCategory::create(['name' => 'Mother- Baby']);
        DrugCategory::create(['name' => 'Pharmacy Package']);
        DrugCategory::create(['name' => 'Prescription Medecines']);
        DrugCategory::create(['name' => 'Vaccination And Immunisation']);
        DrugCategory::create(['name' => 'Vitamins & Supplements']);
        DrugCategory::create(['name' => 'Personal Care']);
        DrugCategory::create(['name' => 'Prescription Medicines']);
        DrugCategory::create(['name' => 'Sexual & Reproductive Health']);

    }
}
