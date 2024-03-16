<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('role')->default('user');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('google_id')->nullable();
            $table->string('facebook_id')->nullable();
            $table->boolean('active_package')->default(false);
            $table->datetime('expected_expiration')->nullable();

            // $table->unsignedBigInteger('class_section_id')->nullable();
            // $table->foreign('class_section_id')->references('id')->on('school_class_sections')->onDelete('set null');

           // Inside the users table migration
            // $table->unsignedBigInteger('user_package_id')->nullable();
            // $table->foreign('user_package_id')->references('id')->on('user_packages')->onDelete('set null');

            
            // Remove duplicate school_id column definition
            // $table->foreignId('teacher_of_school_id')->nullable()->constrained('schools')->onDelete('set null')->comment('Foreign key to schools table for teachers');
            // $table->foreignId('student_of_school_id')->nullable()->constrained('schools')->onDelete('set null')->comment('Foreign key to schools table for students');
            // $table->foreignId('school_id')->nullable()->constrained('schools')->onDelete('set null')->comment('Foreign key to schools table');
            
            $table->json('permissions')->nullable(); // Add permissions column
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
