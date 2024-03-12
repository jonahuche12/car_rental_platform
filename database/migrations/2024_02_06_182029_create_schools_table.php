<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('motto')->nullable();
            $table->text('vision')->nullable();
            $table->string('logo')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->unsignedInteger('total_students')->default(0);
            $table->unsignedInteger('total_teachers')->default(0);
            $table->unsignedInteger('total_staff')->default(0);
            $table->boolean('is_active')->default(false);
            $table->datetime('expected_expiration')->nullable();
            $table->boolean('verified')->default(false);
            $table->boolean('publish')->default(false);
            $table->unsignedBigInteger('school_package_id');
            $table->foreign('school_package_id')->references('id')->on('school_packages')->onDelete('cascade');
            $table->unsignedBigInteger('school_owner_id')->nullable();
            $table->foreign('school_owner_id')->references('id')->on('users')->onDelete('set null')->unique('fk_schools_owner_user');

            // Add more fields as per your specific requirements
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
        Schema::dropIfExists('schools');
    }
}
