<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('academic_session_id');
            $table->unsignedBigInteger('term_id');
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('class_section_id');
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('form_teacher_id')->nullable(); // Nullable form teacher ID
            $table->string('course_name');
            $table->decimal('assignment_score', 8, 2);
            $table->decimal('assessment_score', 8, 2);
            $table->decimal('exam_score', 8, 2);
            $table->decimal('total_score', 8, 2);
            $table->string('grade');
            $table->timestamps();
        
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('academic_session_id')->references('id')->on('academic_sessions')->onDelete('cascade');
            $table->foreign('term_id')->references('id')->on('terms')->onDelete('cascade');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('class_section_id')->references('id')->on('school_class_sections')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('school_classes')->onDelete('cascade');
            $table->foreign('form_teacher_id')->references('id')->on('users')->onDelete('set null'); // Set null on delete
        });

        // Create the pivot table for comments
        Schema::create('student_result_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('student_result_id');
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('class_section_id');
            $table->decimal('total_average_score', 8,2);
            $table->unsignedBigInteger('form_teacher_id');
            $table->unsignedBigInteger('academic_session_id');
            $table->unsignedBigInteger('term_id');
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('class_section_id')->references('id')->on('school_class_sections')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('school_classes')->onDelete('cascade');
            
            $table->foreign('student_result_id')->references('id')->on('student_results')->onDelete('cascade');
            $table->foreign('form_teacher_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('academic_session_id')->references('id')->on('academic_sessions')->onDelete('cascade');
            $table->foreign('term_id')->references('id')->on('terms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_result_comments');
        Schema::dropIfExists('student_results');
    }
}
