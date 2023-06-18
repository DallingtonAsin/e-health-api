<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ICDCode;
use Illuminate\Support\Facades\DB;

class Icd10CodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $filePath = storage_path('app/mysql-dumps/icd10_codes.sql');
            $sql = file_get_contents($filePath);
            DB::unprepared($sql);
            ICDCode::query()->update([
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $this->command->info('icd-10 codes seeded successfully.');
        } catch (\Exception $ex) {
            $this->command->info('Exception message: ' . $ex->getMessage());
        }
    }
}
