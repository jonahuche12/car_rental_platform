<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionCriteriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion_criterias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('academic_session_id');
            $table->unsignedBigInteger('school_class_section_id');
            $table->unsignedBigInteger('school_class_id')->nullable();
            $table->foreign('academic_session_id')->references('id')->on('academic_sessions')->onDelete('cascade');
            $table->foreign('school_class_section_id')->nullable()->references('id')->on('school_class_sections')->onDelete('cascade');
            $table->foreign('school_class_id')->references('id')->on('school_classes')->onDelete('cascade');
            $table->decimal('required_avg_score', 8, 2); // Required average score
            $table->decimal('total_attendance_percentage', 5, 2); // Total attendance percentage
            $table->decimal('compulsory_courses_avg_score', 8, 2); // Average score for compulsory courses
            $table->boolean('student_promoted')->default(false);
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
        Schema::dropIfExists('promotion_criterias');
    }
}
