<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Update written_assessments table
        Schema::table('written_assessments', function (Blueprint $table) {
            $table->dropColumn(['question', 'answer']);
        });

        // Create written_assessment_progress table
        Schema::create('written_assessment_progress', function (Blueprint $table) {
             $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('chapter_id')->constrained()->onDelete('cascade');
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });

        // Create written_assessment_questions table
        Schema::create('written_assessment_questions', function (Blueprint $table) {
             $table->id();
            $table->foreignId('written_assessment_id')->nullable()->constrained('written_assessments');
            $table->text('questions')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('written_assessment_questions');
        Schema::dropIfExists('written_assessment_progress');

        Schema::table('written_assessments', function (Blueprint $table) {
            $table->string('question')->nullable();
            $table->text('answer')->nullable();
        });
    }
};
