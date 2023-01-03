<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_doctors', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->unsignedBigInteger('specialty_id');
            $table->string('title')->nullable();
            $table->string('phone_number');
            $table->string('email')->nullable()->unique();
            $table->string('qualification')->nullable();
            $table->string('profession');
            $table->json('languages')->nullable();
            $table->string('experience');
            $table->string('image');
            $table->double('service_fee');
            $table->timestamps();
            $table->foreign('specialty_id')->references('id')->on('medical_specialties');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medical_doctors');
    }
}
