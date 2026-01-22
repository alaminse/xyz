<?php

use App\Models\Chapter;
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
        Schema::create('chapter_lesson', function (Blueprint $table) {
            $table->foreignId('chapter_id')->nullable()->constrained((new Chapter())->getTable());
            $table->foreignId('lesson_id')->nullable()->constrained((new Lesson())->getTable());
            $table->tinyInteger('sba')->default(0);
            $table->tinyInteger('note')->default(0);
            $table->tinyInteger('mcq')->default(0);
            $table->tinyInteger('flush')->default(0);
            $table->tinyInteger('videos')->default(0);
            $table->tinyInteger('ospe')->default(0);
            $table->tinyInteger('written')->default(0);
            $table->tinyInteger('mock_viva')->default(0);
            $table->tinyInteger('self_assessment')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chapter_lesson');
    }
};
