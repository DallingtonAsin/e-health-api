<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\IcdCodeCategory;

class IcdCodeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $filePath = storage_path('app/mysql-dumps/icd10_categories.sql');
            $sql = file_get_contents($filePath);
            DB::unprepared($sql);
            IcdCodeCategory::query()->update([
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $this->command->info('icd-10 code categories seeded successfully.');
        } catch (\PDOException $ex) {
            $this->command->info('Exception message: ' . $ex->getMessage());
        }
    }
}
