<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAutoApproveToMedicalDoctors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medical_doctors', function (Blueprint $table) {
            $table->boolean('auto_approve')->default(false)->after('is_online');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medical_doctors', function (Blueprint $table) {
            $table->dropColumn('auto_approve');
        });
    }
}
