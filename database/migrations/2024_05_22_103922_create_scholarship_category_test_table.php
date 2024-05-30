<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarshipCategoryTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholarship_category_test', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scholarship_category_id');
            $table->unsignedBigInteger('test_id');
            $table->timestamps();

            // Adding foreign keys and setting up cascade on delete
            $table->foreign('scholarship_category_id')
                  ->references('id')->on('scholarship_categories')
                  ->onDelete('cascade');

            $table->foreign('test_id')
                  ->references('id')->on('tests')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scholarship_category_test');
    }
}
