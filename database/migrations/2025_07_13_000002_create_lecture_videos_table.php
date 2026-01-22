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
        Schema::create('lecture_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->nullable()->constrained((new Chapter())->getTable());
            $table->foreignId('lesson_id')->nullable()->constrained((new Lesson())->getTable());
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->text('youtube_link')->nullable();
            $table->text('uploaded_link')->nullable();
            $table->string('hls_path')->nullable();
            $table->boolean('is_hls_ready')->default(true);
            $table->tinyInteger('isPaid')->default(0);
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
        Schema::dropIfExists('lecture_videos');
    }
};
