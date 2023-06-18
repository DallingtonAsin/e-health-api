<?php

namespace Database\Seeders;

use App\Models\ImageTestCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImageTestCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $filePath = storage_path('app/mysql-dumps/image_test_categories.sql');
            $sql = file_get_contents($filePath);
            DB::unprepared($sql);
            ImageTestCategory::query()->update([
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $this->command->info('Image test categories seeded successfully.');
        } catch (\PDOException $ex) {
            $this->command->info('Exception message: ' . $ex->getMessage());
        }
    }
}
