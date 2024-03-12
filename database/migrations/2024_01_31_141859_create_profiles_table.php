<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // Foreign key to users table

            // Common Fields
            $table->string('role');
            $table->string('full_name');
            $table->string('country');
            $table->string('state');
            $table->string('city');
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('profile_picture')->nullable();

            // Contact Information
            $table->string('email');
            $table->string('phone_number')->nullable();
            $table->text('address')->nullable();

            // Additional Fields for Student Profile
            $table->string('student_id')->nullable();
            $table->string('class_id')->nullable();
            $table->string('current_class')->nullable();
            $table->string('class_grade')->nullable();
            $table->string('section')->nullable();
            $table->string('roll_number')->nullable();

            // Additional Fields for Teacher Profile
            $table->string('teacher_id')->nullable();
            $table->text('subjects_taught')->nullable();
            $table->string('classes_assigned')->nullable();
            $table->text('qualifications')->nullable();
            $table->text('certifications')->nullable();
            $table->integer('years_of_experience')->nullable();

            // Additional Fields for Guardian Profile
            $table->string('relationship')->nullable();
            $table->string('ward_name')->nullable();
            $table->string('ward_class')->nullable();

            // Additional Fields for Staff Profile
            $table->string('staff_id')->nullable();
            $table->string('department')->nullable();
            $table->string('position')->nullable();
            $table->text('staff_qualifications')->nullable();
            $table->text('staff_certifications')->nullable();
            $table->integer('years_of_service')->nullable();

            $table->boolean('admin_confirmed')->default(false);
            $table->boolean('teacher_confirmed')->default(false);
            $table->boolean('student_confirmed')->default(false);
            $table->boolean('class_confirmed')->default(false);
            $table->boolean('staff_confirmed')->default(false);
            $table->boolean('class_confirmed')->default(false);


            $table->unsignedBigInteger('class_id')->nullable();
            $table->foreign('class_id')->references('id')->on('school_classes')->onDelete('set null');


            // $table->boolean('permission_confirm_student')->default(false);
            // $table->boolean('permission_confirm_admin')->default(false);
            // $table->boolean('permision_confirm_teacher')->default(false);
            // $table->boolean('permission_create_lesson')->default(false);
            // $table->boolean('permision_create_course')->default(false);
            // $table->boolean('permision_create_class')->default(false);
            // $table->boolean('permission_create_event')->default(false);
            // $table->boolean('permision_confirm_staff')->default(false);

            
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
        Schema::dropIfExists('profiles');
    }
}
