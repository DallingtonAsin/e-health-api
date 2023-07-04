<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeColumnsNullableInMedicalHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medical_histories', function (Blueprint $table) {
            $table->text('presenting_complaint')->nullable()->change();
            $table->text('past_medical_history')->nullable()->change();
            $table->text('drug_allergies')->nullable()->change();
            $table->text('findings')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medical_histories', function (Blueprint $table) {
            $table->text('presenting_complaint')->change();
            $table->text('past_medical_history')->change();
            $table->text('drug_allergies')->change();
            $table->text('findings')->change();
        });
    }
}
