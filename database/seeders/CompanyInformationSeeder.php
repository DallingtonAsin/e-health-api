<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanyInformation;

class CompanyInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CompanyInformation::create([
            'name' => 'Vastel',
            'mobile_phone_no' => '+256772409074',
            'sms_phone_no' => '+256772409074',
            'whatsapp_number' => '+256772409074',
            'email' => 'info@vastel.com'
        ]);
    }
}
