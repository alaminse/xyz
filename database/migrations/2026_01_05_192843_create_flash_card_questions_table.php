<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('flash_card_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('flash_card_id');
            $table->text('question');
            $table->string('slug')->unique();
            $table->text('answer');
            $table->timestamps();

            $table->foreign('flash_card_id')->references('id')->on('flash_cards')->onDelete('cascade');
            $table->index('flash_card_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('flash_card_questions');
    }
};
