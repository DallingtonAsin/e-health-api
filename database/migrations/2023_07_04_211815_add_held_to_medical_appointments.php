<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddHeldToMedicalAppointments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            DB::statement("ALTER TABLE medical_appointments MODIFY status ENUM('pending', 'confirmed', 'completed', 'cancelled', 'expired', 'held', 'rescheduled') DEFAULT 'pending'");
           
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
            DB::statement("ALTER TABLE medical_appointments MODIFY status ENUM('pending', 'confirmed', 'completed', 'cancelled', 'expired', 'held', 'rescheduled') DEFAULT 'pending'");
    
    }
}
