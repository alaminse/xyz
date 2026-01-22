<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sba_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sba_id');
            $table->unsignedBigInteger('note_id');
            $table->text('question')->nullable();
            $table->string('slug')->nullable();
            $table->string('option1')->nullable();
            $table->string('option2')->nullable();
            $table->string('option3')->nullable();
            $table->string('option4')->nullable();
            $table->string('option5')->nullable();
            $table->string('correct_option')->nullable(); // String করা হয়েছে
            $table->text('explain')->nullable();
            $table->timestamps();

            $table->foreign('sba_id')->references('id')->on('sbas')->onDelete('cascade');
            $table->foreign('note_id')->references('id')->on('notes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sba_questions');
    }
};
