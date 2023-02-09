<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AppointmentType;

class AppointmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AppointmentType::create(['name' => 'In Person']);
        AppointmentType::create(['name' => 'Audio Call']);
        AppointmentType::create(['name' => 'Video Session']);

    }
}
