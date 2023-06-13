<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LabTestCategory;

class LabTestCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LabTestCategory::create(['name' => 'Blood test']);
        LabTestCategory::create(['name' => 'Urinalysis']);
        LabTestCategory::create(['name' => 'aPTT']);
        LabTestCategory::create(['name' => 'Comprehensive metabolic panel']);
        LabTestCategory::create(['name' => 'Thyroid function tests']);
        LabTestCategory::create(['name' => 'Antibody']);
        LabTestCategory::create(['name' => 'Bilirubin test']);
        LabTestCategory::create(['name' => 'Pap test']);
        LabTestCategory::create(['name' => 'Cholesterol test']);
        LabTestCategory::create(['name' => 'Basic metabolic panel']);
        LabTestCategory::create(['name' => 'Blood urea nitrogen']);
        LabTestCategory::create(['name' => 'Blood glucose test']);
        LabTestCategory::create(['name' => 'Blood culture']);
        LabTestCategory::create(['name' => 'Creatinine test']);
        LabTestCategory::create(['name' => 'Blood smear']);
    }
}
