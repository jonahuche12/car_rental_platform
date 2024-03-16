<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('general_name');
            $table->boolean('compulsory')->default('false');
            $table->string('code');
            $table->timestamps();
            $table->softDeletes(); // Add this line for soft delete support
        });

        // Pivot table for teachers
        Schema::create('course_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_id')->constrained('school_class_sections')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Pivot table for students
        Schema::create('course_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Pivot table for class sections
        Schema::create('course_class', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_id')->constrained('school_class_sections')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade'); // Add teacher_id column
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_class');
        Schema::dropIfExists('course_student');
        Schema::dropIfExists('course_teacher');
        Schema::dropIfExists('courses');
    }
}
