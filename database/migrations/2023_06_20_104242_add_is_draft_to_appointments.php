<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsDraftToAppointments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medical_appointments', function (Blueprint $table) {
            $table->boolean('is_draft')->default(true)->after('alert_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medical_appointments', function (Blueprint $table) {
            $table->dropColumn('is_draft');
        });
    }
}
