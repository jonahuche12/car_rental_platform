<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('school_packages', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Name of the school package
        $table->text('description')->nullable(); // Description of the school package (nullable as it might not be provided)
        $table->string('picture'); 
        $table->decimal('price', 8, 2); // Price of the school package
        $table->unsignedInteger('duration_in_days'); // Duration of the school package in days
        $table->unsignedInteger('max_students'); // Number of students included in the package
        $table->unsignedInteger('max_admins'); // Number of admin users included in the package
        $table->unsignedInteger('max_teachers'); // Number of teacher users included in the package
        $table->unsignedInteger('max_classes');
        $table->boolean('is_active')->default(false); // Whether the school package is currently active or not
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
        Schema::dropIfExists('school_packages');
    }
}
