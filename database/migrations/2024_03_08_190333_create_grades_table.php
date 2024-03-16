<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('assignment_id')->nullable();
            $table->unsignedBigInteger('assessment_id')->nullable();
            $table->unsignedBigInteger('exam_id')->nullable();
            $table->string('academic_session');
            $table->decimal('score', 5, 2);
            $table->timestamps();
            $table->softDeletes(); // Add this line for soft delete support

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('assignment_id')->references('id')->on('assignments')->onDelete('set null');
            $table->foreign('assessment_id')->references('id')->on('assessments')->onDelete('set null');
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grades');
    }
}
