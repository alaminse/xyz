<?php

use App\Models\Chapter;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flash_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained((new Chapter())->getTable());
            $table->foreignId('lesson_id')->nullable()->constrained((new Lesson())->getTable());
            $table->boolean('isPaid')->default(0);
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('flash_cards');
    }
};
