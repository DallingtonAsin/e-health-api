<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Language::create(['name' => 'English']);
        Language::create(['name' => 'Luganda']);
        Language::create(['name' => 'Runyankore']);
        Language::create(['name' => 'Rukiga']);
        Language::create(['name' => 'Iteso']);
        Language::create(['name' => 'Lugbara']);
        Language::create(['name' => 'Runyoro']);

    }
}
