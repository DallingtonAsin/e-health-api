<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientDepositTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_deposit_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->double('amount');
            $table->timestamp('transaction_date');
            $table->string('payment_method');
            $table->text('note')->nullable();
            $table->string('request_id');
            $table->enum('status', ['PENDING', 'SENT', 'SUCCESS', 'FAILED'])->default('PENDING');
            $table->string('status_code')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('description')->nullable();
            $table->string('error_message')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('patients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_deposit_transactions');
    }
}
