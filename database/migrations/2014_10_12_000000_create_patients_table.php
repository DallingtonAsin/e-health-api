<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_type_id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('country_code');
            $table->string('phone_number');
            $table->string('email')->nullable()->unique();
            $table->string('address')->nullable();
            $table->enum('gender', ['Male', 'Female'])->nullable();
            $table->date('dob')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('current_version')->nullable();
            $table->string('unique_device_id')->nullable();
            $table->string('fcm_token')->nullable();
            $table->string('otp')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_blocked')->default(false);
            $table->boolean('profile_status')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('user_type_id')->references('id')->on('user_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
}
