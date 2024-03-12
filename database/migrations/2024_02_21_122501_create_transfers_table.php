<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->decimal('amount', 10, 2); 
            $table->unsignedBigInteger('id_paid_for');
            $table->string('payment_session_id')->nullable();
            $table->boolean('payment_marked')->default(false);
            $table->enum('paid_for',['school_activation','school_connects','user_activation']);
            $table->boolean('payment_confirmed')->default(false);
            $table->string('confirmation_link')->nullable();
            $table->string('status')->default('pending');
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
        Schema::dropIfExists('transfers');
    }
}
