<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurriculaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curricula', function (Blueprint $table) {
            $table->id();
            $table->string('country');
            $table->string('subject');
            $table->string('theme');
            $table->text('description')->nullable();
            $table->string('class_level');
            $table->timestamps();
        });

        // Pivot table for the many-to-many relationship between curricula and topics
        Schema::create('curricula_topics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('curriculum_id');
            $table->string('topic');
            $table->text('description')->nullable(); // Include description directly in the pivot table
            $table->timestamps();

            $table->foreign('curriculum_id')->references('id')->on('curricula')->onDelete('cascade');

            // The unique constraint should be on curriculum_id and topic, not curriculum_id and id
            $table->unique(['curriculum_id', 'topic']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('curricula_topics');
        Schema::dropIfExists('curricula');
    }

}
