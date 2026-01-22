<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // ১. নতুন mcq_questions টেবিল তৈরি
        Schema::create('mcq_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mcq_id');
            $table->unsignedBigInteger('note_id');
            $table->text('question');
            $table->string('slug')->unique();
            $table->text('option1');
            $table->boolean('answer1')->default(0);
            $table->text('option2');
            $table->boolean('answer2')->default(0);
            $table->text('option3');
            $table->boolean('answer3')->default(0);
            $table->text('option4');
            $table->boolean('answer4')->default(0);
            $table->text('option5');
            $table->boolean('answer5')->default(0);
            $table->text('explain');
            $table->timestamps();

            $table->foreign('mcq_id')->references('id')->on('mcqs')->onDelete('cascade');
            $table->index('mcq_id');
            $table->foreign('note_id')->references('id')->on('notes')->onDelete('cascade');
            $table->index('note_id');
        });

    }

    public function down()
    {
        Schema::dropIfExists('mcq_questions');
    }
};
