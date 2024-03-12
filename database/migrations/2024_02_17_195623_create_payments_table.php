<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('amount');
            $table->unsignedBigInteger('reference_id');
            $table->enum('paid_for',['school_activation','school_connects','user_activation']);
            $table->timestamp('paid_at')->nullable();
            $table->string('channel')->nullable();
            $table->string('currency')->nullable();
            $table->string('ip_addr')->nullable();
            $table->boolean('successful')->default(false);
            $table->string('paystack_reference')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
