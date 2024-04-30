<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID of the teacher who created the lesson
            $table->string('title');
            $table->string('subject')->nullable();
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('video_url');
            $table->unsignedInteger('school_connects_required')->default(9); // Number of school connects required
            $table->boolean('published')->default(false);
            $table->timestamps();
        });

        // Pivot table for many-to-many relationship between lessons and playlists
        Schema::create('lesson_playlist', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lesson_id');
            $table->unsignedBigInteger('playlist_id');
            $table->timestamps();

            // Define foreign keys
            $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('cascade');
            $table->foreign('playlist_id')->references('id')->on('playlists')->onDelete('cascade');
        });

        // Pivot table for tracking users who have accessed lessons
        Schema::create('lesson_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lesson_id');
            $table->unsignedBigInteger('user_id');
            $table->string('role'); // Role of the user in relation to the lesson (teacher/student)
            $table->timestamps();

            // Define foreign keys
            $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Add unique constraint to ensure each lesson-user combination is unique
            $table->unique(['lesson_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lesson_user');
        Schema::dropIfExists('lesson_playlist');
        Schema::dropIfExists('lessons');
    }
}
