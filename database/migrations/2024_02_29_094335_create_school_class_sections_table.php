<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolClassSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_class_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('main_form_teacher_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            // Foreign key relationships
            $table->foreign('class_id')->references('id')->on('school_classes')->onDelete('cascade');
            $table->foreign('main_form_teacher_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('form_teacher_class_section', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('class_section_id');
            $table->timestamps();

            // Foreign key relationships
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('class_section_id')->references('id')->on('school_class_sections')->onDelete('cascade');

            $table->unique(['user_id', 'class_section_id']);
        });

        Schema::create('school_class_section_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('class_section_id');
            $table->timestamps();

            // Foreign key relationships
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('class_section_id')->references('id')->on('school_class_sections')->onDelete('cascade');

            $table->unique(['user_id', 'class_section_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('form_teacher_class_section');
        Schema::dropIfExists('school_class_section_user');
        Schema::dropIfExists('school_class_sections');
    }
}
