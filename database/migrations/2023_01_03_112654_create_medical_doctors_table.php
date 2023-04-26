<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


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
            $table->unsignedBigInteger('user_type_id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->unsignedBigInteger('specialty_id')->nullable();
            $table->string('country_code');
            $table->string('phone_number');
            $table->string('email')->nullable()->unique();
            $table->date('dob')->nullable();
            $table->string('address')->nullable();
            $table->enum('gender', ['Male', 'Female'])->nullable();
            $table->string('qualification')->nullable();
            $table->unsignedBigInteger('primary_facility_id')->nullable();
            $table->json('other_facilities')->nullable();
            $table->string('training_institute')->nullable();
            $table->string('umdp_lincense_id')->nullable();
            $table->text('bio_summary')->nullable();
            $table->double('service_fee')->nullable();
            $table->string('password')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('current_version')->nullable();
            $table->string('unique_device_id')->nullable();
            $table->string('fcm_token')->nullable();
            $table->string('otp')->nullable();
            $table->string('image')->nullable();
            $table->boolean('profile_status')->default(false);
            $table->boolean('is_registered')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_blocked')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();

            $table->foreign('user_type_id')->references('id')->on('user_types');
            $table->foreign('specialty_id')->references('id')->on('medical_specialties');
            $table->foreign('primary_facility_id')->references('id')->on('medical_facilities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('medical_doctors');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
